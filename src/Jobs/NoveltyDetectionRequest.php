<?php

namespace Biigle\Modules\Maia\Jobs;

use File;
use Queue;
use Exception;
use ImageCache;
use Biigle\Modules\Maia\MaiaJob;

/**
 * This job is executed on a machine with GPU access.
 */
class NoveltyDetectionRequest extends JobRequest
{
    /**
     * Disable the timeout of the Laravel queue worker because this job may run long.
     *
     * @var int
     */
    public $timeout = 0;

    /**
     * Execute the job
     */
    public function handle()
    {
        $this->createTmpDir();
        $images = $this->getGenericImages();

        ImageCache::batch($images, function ($images, $paths) {
            $script = config('maia.novelty_detection_script');
            $path = $this->createInputJson($images, $paths);
            $this->python("{$script} {$path}");
        });

        $annotations = $this->parseAnnotations($images);
        $this->dispatchResponse($annotations);
        $this->cleanup();
    }

    /**
     * Create the JSON file that is the input to the novelty detection script.
     *
     * @param array $images GenericImage instances.
     * @param array $paths Paths to the cached image files.
     * @return string Input JSON file path.
     */
    protected function createInputJson($images, $paths)
    {
        $path = "{$this->tmpDir}/input.json";
        $imagesMap = [];
        foreach ($images as $index => $image) {
            $imagesMap[$image->getId()] = $paths[$index];
        }

        $content = [
            'nd_clusters' => intval($this->jobParams['nd_clusters']),
            'nd_patch_size' => intval($this->jobParams['nd_patch_size']),
            'nd_threshold' => intval($this->jobParams['nd_threshold']),
            'nd_latent_size' => floatval($this->jobParams['nd_latent_size']),
            'nd_trainset_size' => intval($this->jobParams['nd_trainset_size']),
            'nd_epochs' => intval($this->jobParams['nd_epochs']),
            'nd_stride' => intval($this->jobParams['nd_stride']),
            'nd_ignore_radius' => intval($this->jobParams['nd_ignore_radius']),
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'available_bytes' => intval(config('maia.available_bytes')),
            'max_workers' => intval(config('maia.max_workers')),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * Parse the output JSON files to get the array of annotations for each image.
     *
     * @param array $images GenericImage instances.
     *
     * @return array
     */
    protected function parseAnnotations($images)
    {
        $annotations = [];
        $isNull = 0;

        foreach ($images as $image) {
            $annotations[$image->getId()] = $this->parseAnnotationsFile($image);
            if (is_null($annotations[$image->getId()])) {
                $isNull += 1;
            }
        }

        // For many images (as it is common) it might be not too bad if novelty detection
        // failed for some of them. We still get enough training proposals and don't want
        // to execute the long running novelty detection again. But if too many images
        // failed, abort.
        if (($isNull / count($images)) > 0.1) {
            throw new Exception('Unable to parse more than 10 % of the output JSON files.');
        }

        return $annotations;
    }

    /**
     * Parse the output JSON file of a single image.
     *
     * @param GenericImage $image
     *
     * @return array
     */
    protected function parseAnnotationsFile($image)
    {
        $id = $image->getId();

        return json_decode(File::get("{$this->tmpDir}/{$id}.json"), true);
    }

    /**
     * Dispatch the job to store the novelty detection results.
     *
     * @param array $annotations
     */
    protected function dispatchResponse($annotations)
    {
        $this->dispatch(new NoveltyDetectionResponse($this->jobId, $annotations));
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        $this->dispatch(new NoveltyDetectionFailure($this->jobId, $e));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTmpDirPath()
    {
        return parent::getTmpDirPath()."-novelty-detection";
    }
}
