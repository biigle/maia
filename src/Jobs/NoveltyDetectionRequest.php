<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\MaiaJob;
use Exception;
use File;
use FileCache;
use Queue;

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

        try {
            $images = $this->getGenericImages();

            FileCache::batch($images, function ($images, $paths) {
                $script = config('maia.novelty_detection_script');
                $path = $this->createInputJson($images, $paths);
                $this->python("{$script} {$path}");
            });

            $annotations = $this->parseAnnotations($images);
            $limit = config('maia.training_proposal_limit');
            $annotations = $this->maybeLimitAnnotations($annotations, $limit);
            $this->dispatchResponse($annotations);
        } finally {
            $this->cleanup();
        }
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
            'max_workers' => intval(config('maia.max_workers')),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
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


    /**
     * Apply the limit for the maximum number of annotations.
     *
     * @param array $annotations
     * @param int $limit
     *
     * @return array
     */
    protected function maybeLimitAnnotations($annotations, $limit)
    {
        if (count($annotations) <= $limit) {
            return $annotations;
        }

        usort($annotations, function ($a, $b) {
            // The fourth array element is the score of the annotation. We want to sort
            // the annotations by descending scores.
            return round($b[4] - $a[4]);
        });

        return array_slice($annotations, 0, $limit);
    }
}
