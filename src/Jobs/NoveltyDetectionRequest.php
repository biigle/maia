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
        $this->ensureTmpDir();
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
            'clusters' => intval($this->jobParams['clusters']),
            'patch_size' => intval($this->jobParams['patch_size']),
            'threshold' => intval($this->jobParams['threshold']),
            'latent_size' => floatval($this->jobParams['latent_size']),
            'trainset_size' => intval($this->jobParams['trainset_size']),
            'epochs' => intval($this->jobParams['epochs']),
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'available_bytes' => intval(config('maia.available_bytes')),
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
}
