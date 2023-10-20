<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\GenericImage;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidateFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionComplete;
use Biigle\Volume;
use DB;
use Exception;
use File;
use FileCache;
use Queue;

class ObjectDetection extends DetectionJob
{
    /**
     * Selected training proposals.
     *
     * @var array
     */
    protected $trainingProposals;

    /**
     * URL of the volume for knowledge transfer (if any).
     *
     * @var string
     */
    protected $knowledgeTransferVolumeUrl;

    /**
     * Filenames of the images of the knowledge transfer volume, indexed by their IDs.
     *
     * @var array
     */
    protected $knowledgeTransferImages;

    /**
     * Create a new instance
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        parent::__construct($job);
        // Make sure to convert the annotations to arrays because it is more efficient
        // and the GPU server cannot instantiate MaiaAnnotation objects (as they depend
        // on biigle/core).
        $this->trainingProposals = $this->bundleTrainingProposals($job);

        if ($this->shouldUseKnowledgeTransfer()) {
            $volume = Volume::find($this->job->params['kt_volume_id']);
            $this->knowledgeTransferVolumeUrl = $volume->url;
            $this->knowledgeTransferImages = $volume->images()
                ->pluck('filename', 'id')
                ->toArray();
        }
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        $this->createTmpDir();

        $images = $this->getGenericImages();

        if ($this->shouldUseKnowledgeTransfer()) {
            $datasetImages = $this->getKnowledgeTransferImages();
        } else {
            $datasetImages = $images;
        }

        // All images that contain selected training proposals.
        $relevantImages = array_filter($datasetImages, function ($image) {
            return array_key_exists($image->getId(), $this->trainingProposals);
        });

        $output = FileCache::batch($relevantImages, function ($images, $paths) {
            $datasetOutputPath = $this->generateDataset($images, $paths);
            // Training doesn't need the images and paths directly but expects the
            // files to be there in the cache.
            $trainingOutputPath = $this->performTraining($datasetOutputPath);

            return [$datasetOutputPath, $trainingOutputPath];
        });

        $this->performInference($images, $output[0], $output[1]);

        $annotations = $this->parseAnnotations($images);

        // Make sure to roll back any DB modifications if an error occurs.
        DB::transaction(function () use ($annotations) {
            $this->createMaiaAnnotations($annotations);
            $this->updateJobState();
        });

        $this->cleanup();
    }

    /**
     * Determine whether knowledge transfer should be performed in this job.
     *
     * @return bool
     */
    protected function shouldUseKnowledgeTransfer()
    {
        return array_key_exists('training_data_method', $this->job->params) && in_array($this->job->params['training_data_method'], ['knowledge_transfer', 'area_knowledge_transfer']);
    }

    /**
     * Bundle the training proposals to be sent to the GPU server.
     *
     * @param MaiaJob $job
     *
     * @return array
     */
    protected function bundleTrainingProposals(MaiaJob $job)
    {
        return $job->trainingProposals()
            ->selected()
            ->select('image_id', 'points')
            ->get()
            ->groupBy('image_id')
            ->map(function ($proposals) {
                return $proposals->pluck('points')->map(function ($proposal) {
                    // The circles of the proposals are drawn by OpenCV and this expects
                    // integers. As we can shave off a few bytes of job payload this
                    // way, we parse the coordinates here instead of in the Python
                    // script.
                    return array_map(function ($value) {
                        return intval(round($value));
                    }, $proposal);
                });
            })
            ->toArray();
    }

    /**
     * Generate the training dataset for the object detection model.
     *
     * @param array $images GenericImage instances.
     * @param array $paths Paths to the cached image files.
     *
     * @return string Path to the JSON output file.
     */
    protected function generateDataset($images, $paths)
    {
        $outputPath = "{$this->tmpDir}/output-dataset.json";

        $imagesMap = $this->buildImagesMap($images, $paths);
        $inputPath = $this->createDatasetJson($imagesMap, $outputPath);
        $script = config('maia.mmdet_dataset_script');
        $this->python("{$script} {$inputPath}", 'dataset-log.txt');

        return $outputPath;
    }

    /**
     * Create the JSON file that is the input to the dataset generation script.
     *
     * @param array $imagesMap Map from image IDs to cached file paths.
     * @param string $outputJsonPath Path to the output file of the script.
     *
     * @return string Input JSON file path.
     */
    protected function createDatasetJson($imagesMap, $outputJsonPath)
    {
        $path = "{$this->tmpDir}/input-dataset.json";
        $content = [
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'max_workers' => intval(config('maia.max_workers')),
            'training_proposals' => $this->trainingProposals,
            'output_path' => $outputJsonPath,
        ];

        if ($this->shouldUseKnowledgeTransfer()) {
            $content['kt_scale_factors'] = $this->job->params['kt_scale_factors'];
        }

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Perform training of object detection model.
     *
     * @param string $datasetOutputPath Path to the JSON output of the dataset generator.
     *
     * @return string Path to the JSON output file of the training script.
     */
    protected function performTraining($datasetOutputPath)
    {
        $outputPath = "{$this->tmpDir}/output-training.json";
        $this->maybeDownloadWeights(config('maia.backbone_model_url'), config('maia.backbone_model_path'));
        $this->maybeDownloadWeights(config('maia.model_url'), config('maia.model_path'));
        $inputPath = $this->createTrainingJson($outputPath);
        $script = config('maia.mmdet_training_script');
        $this->python("{$script} {$inputPath} {$datasetOutputPath}", 'training-log.txt');

        return $outputPath;
    }

    /**
     * Downloads the model pretrained weights if they weren't downloaded yet.
     *
     * @param string $from
     * @param string $to
     *
     */
    protected function maybeDownloadWeights($from, $to)
    {
        if (!File::exists($to)) {
            $this->ensureDirectory(dirname($to));
            $success = @copy($from, $to);

            if (!$success) {
                throw new Exception("Failed to download model weights from '{$from}'.");
            }
        }
    }

    /**
     * Create the JSON file that is the input to the training script.
     *
     * @param string $outputJsonPath Path to the output file of the script.
     *
     * @return string Input JSON file path.
     */
    protected function createTrainingJson($outputJsonPath)
    {
        $path = "{$this->tmpDir}/input-training.json";
        $content = [
            'tmp_dir' => $this->tmpDir,
            'max_workers' => intval(config('maia.max_workers')),
            'output_path' => $outputJsonPath,
            'base_config' => config('maia.mmdet_base_config'),
            'batch_size' => config('maia.mmdet_train_batch_size'),
            'backbone_model_path' => config('maia.backbone_model_path'),
            'model_path' => config('maia.model_path'),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Perform inference with the trained object detection model.
     *
     * @param array $images GenericImage instances.
     * @param string $datasetOutputPath Path to the JSON output of the dataset generator.
     * @param string $trainingOutputPath Path to the JSON output of the training script.
     */
    protected function performInference($images, $datasetOutputPath, $trainingOutputPath)
    {
        FileCache::batch($images, function ($images, $paths) use ($datasetOutputPath, $trainingOutputPath) {
            $imagesMap = $this->buildImagesMap($images, $paths);
            $inputPath = $this->createInferenceJson($imagesMap);
            $script = config('maia.mmdet_inference_script');
            $this->python("{$script} {$inputPath} {$datasetOutputPath} {$trainingOutputPath}", 'inference-log.txt');
        });
    }

    /**
     * Create the JSON file that is the input to the inference script.
     *
     * @param array $imagesMap Map from image IDs to cached file paths.
     *
     * @return string Input JSON file path.
     */
    protected function createInferenceJson($imagesMap)
    {
        $path = "{$this->tmpDir}/input-inference.json";
        $content = [
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'max_workers' => intval(config('maia.max_workers')),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Build the map from image ID to path of the cached image file.
     *
     * @param array $images GenericImage instances.
     * @param array $paths Cached image file paths.
     *
     * @return array
     */
    protected function buildImagesMap($images, $paths)
    {
        $imagesMap = [];
        foreach ($images as $index => $image) {
            $imagesMap[$image->getId()] = $paths[$index];
        }

        return $imagesMap;
    }

    /**
     * Dispatch the job to store the object detection results.
     *
     * @param array $annotations
     */
    protected function dispatchResponse($annotations)
    {
        $this->dispatch(new ObjectDetectionResponse($this->job->id, $annotations));
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        Queue::push(new ObjectDetectionFailure($this->job->id, $e));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTmpDirPath()
    {
        return parent::getTmpDirPath()."-object-detection";
    }

    /**
     * Create GenericImage instances for the images of the knowledge transfer volume.
     *
     * @return array
     */
    protected function getKnowledgeTransferImages()
    {
        $images = [];
        foreach ($this->knowledgeTransferImages as $id => $filename) {
            $images[$id] = new GenericImage($id, "{$this->knowledgeTransferVolumeUrl}/{$filename}");
        }

        return $images;
    }

    /**
     * {@inheritdoc}
     */
    protected function insertAnnotationChunk(array $chunk)
    {
        AnnotationCandidate::insert($chunk);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateJobState()
    {
        $this->job->state_id = State::annotationCandidatesId();
        $this->job->save();
    }
}
