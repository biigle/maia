<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Biigle\Modules\Maia\TrainingProposal;
use DB;
use Exception;
use File;
use FileCache;
use Queue;

class NoveltyDetection extends DetectionJob
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

        FileCache::batch($images, function ($images, $paths) {
            $script = config('maia.novelty_detection_script');
            $path = $this->createInputJson($images, $paths);
            $this->python("{$script} {$path}");
        });

        $annotations = $this->parseAnnotations($images);
        $limit = config('maia.training_proposal_limit');
        $annotations = $this->maybeLimitAnnotations($annotations, $limit);

        // Make sure to roll back any DB modifications if an error occurs.
        DB::transaction(function () use ($annotations) {
            $this->createMaiaAnnotations($annotations);
            $this->updateJobState();
        });

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

        $params = $this->job->params;

        $content = [
            'nd_clusters' => intval($params['nd_clusters']),
            'nd_patch_size' => intval($params['nd_patch_size']),
            'nd_threshold' => intval($params['nd_threshold']),
            'nd_latent_size' => floatval($params['nd_latent_size']),
            'nd_trainset_size' => intval($params['nd_trainset_size']),
            'nd_epochs' => intval($params['nd_epochs']),
            'nd_stride' => intval($params['nd_stride']),
            'nd_ignore_radius' => intval($params['nd_ignore_radius']),
            'images' => $imagesMap,
            'tmp_dir' => $this->tmpDir,
            'max_workers' => intval(config('maia.max_workers')),
        ];

        File::put($path, json_encode($content, JSON_UNESCAPED_SLASHES));

        return $path;
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        Queue::push(new NoveltyDetectionFailure($this->job->id, $e));
    }

    /**
     * {@inheritdoc}
     */
    protected function getTmpDirPath()
    {
        return parent::getTmpDirPath()."-novelty-detection";
    }

    /**
     * {@inheritdoc}
     */
    protected function insertAnnotationChunk(array $chunk)
    {
        TrainingProposal::insert($chunk);
    }

    /**
     * {@inheritdoc}
     */
    protected function updateJobState()
    {
        $this->job->state_id = State::trainingProposalsId();
        $this->job->save();
    }
}
