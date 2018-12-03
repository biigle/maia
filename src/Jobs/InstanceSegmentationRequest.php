<?php

namespace Biigle\Modules\Maia\Jobs;

use File;
use Exception;
use ImageCache;
use Biigle\Modules\Maia\MaiaJob;

class InstanceSegmentationRequest extends JobRequest
{
    /**
     * Selected training proposals.
     *
     * @var array
     */
    protected $trainingProposals;

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
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        $this->createTmpDir();
        $images = $this->getGenericImages();

        $datasetOutputPath = $this->generateDataset($images);
        $trainingOutputPath = $this->performTraining($datasetOutputPath);
        // $this->performInference($images, $trainingOutput);

        // $annotations = $this->parseAnnotations($images);
        // $this->dispatchResponse($annotations);

        // $this->cleanup();
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
     * Generate the training dataset for Mask R-CNN.
     *
     * @param array $images GenericImage instances.
     *
     * @return string Path to the JSON output file.
     */
    protected function generateDataset($images)
    {
        $outputPath = "{$this->tmpDir}/output-dataset.json";

        // All images that contain selected training proposals.
        $relevantImages = array_filter($images, function ($image) {
            return array_key_exists($image->getId(), $this->trainingProposals);
        });

        ImageCache::batch($relevantImages, function ($images, $paths) use ($outputPath) {
            $imagesMap = $this->buildImagesMap($images, $paths);
            $inputPath = $this->createDatasetJson($imagesMap, $outputPath);
            $script = config('maia.mrcnn_dataset_script');
            $this->python("{$script} {$inputPath}", 'dataset-log.txt');
        });

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
            'available_bytes' => intval(config('maia.available_bytes')),
            'max_workers' => intval(config('maia.max_workers')),
            'training_proposals' => $this->trainingProposals,
            'output_path' => $outputJsonPath,
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Perform training of Mask R-CNN.
     *
     * @param string $datasetOutputPath Path to the JSON output of the dataset generator.
     *
     * @return string Path to the JSON output file of the training script.
     */
    protected function performTraining($datasetOutputPath)
    {
        $outputPath = "{$this->tmpDir}/output-training.json";
        $this->maybeDownloadCocoModel();
        $inputPath = $this->createTrainingJson($outputPath);
        $script = config('maia.mrcnn_training_script');
        $this->python("{$script} {$inputPath} {$datasetOutputPath}", 'training-log.txt');

        return $outputPath;
    }

    /**
     * Downloads the Mask R-CNN COCO pretrained weights if they weren't downloaded yet.
     */
    protected function maybeDownloadCocoModel()
    {
        $path = config('maia.coco_model_path');
        if (!File::exists($path)) {
            $this->ensureDirectory(dirname($path));
            $url = config('maia.coco_model_url');
            $success = @copy($url, $path);

            if (!$success) {
                throw new Exception("Failed to download Mask R-CNN weights from '{$url}'.");
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
            'is_epochs_head' => intval($this->jobParams['is_epochs_head']),
            'is_epochs_all' => intval($this->jobParams['is_epochs_all']),
            'tmp_dir' => $this->tmpDir,
            'available_bytes' => intval(config('maia.available_bytes')),
            'max_workers' => intval(config('maia.max_workers')),
            'output_path' => $outputJsonPath,
            'coco_model_path' => config('maia.coco_model_path'),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Perform inference with the trained Mask R-CNN.
     *
     * @param array $images GenericImage instances.
     * @param array $params Output of the training script.
     */
    protected function performInference($images, $params)
    {
        ImageCache::batch($images, function ($images, $paths) use ($params) {
            $imagesMap = $this->buildImagesMap($images, $paths);
            $inputPath = $this->createInferenceJson($imagesMap, $params);
            $script = config('maia.mrcnn_inference_script');
            $this->python("{$script} {$inputPath}", 'inference-log.txt');
        });
    }

    /**
     * Create the JSON file that is the input to the inference script.
     *
     * @param array $imagesMap Map from image IDs to cached file paths.
     * @param array $params Output of the training script.
     *
     * @return string Input JSON file path.
     */
    protected function createInferenceJson($imagesMap, $params)
    {
        $path = "{$this->tmpDir}/input-inference.json";
        $content = [
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'available_bytes' => intval(config('maia.available_bytes')),
            'max_workers' => intval(config('maia.max_workers')),
            // ...
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
     * Dispatch the job to store the instance segmentation results.
     *
     * @param array $annotations
     */
    protected function dispatchResponse($annotations)
    {
        $this->dispatch(new InstanceSegmentationResponse($this->jobId, $annotations));
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        $this->dispatch(new InstanceSegmentationFailure($this->jobId, $e));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTmpDirPath()
    {
        return parent::getTmpDirPath()."-instance-segmentation";
    }
}
