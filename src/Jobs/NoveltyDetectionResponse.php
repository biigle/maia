<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Biigle\Modules\Maia\TrainingProposal;
use Exception;
use Queue;

class NoveltyDetectionResponse extends JobResponse
{
    /**
     * {@inheritdoc}
     */
    protected function getExpectedJobStateId()
    {
        return State::noveltyDetectionId();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Queue::push(new NoveltyDetectionFailure($this->jobId, $exception));
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
    protected function getCreatedAnnotations(MaiaJob $job)
    {
        return $job->trainingProposals();
    }

    /**
     * {@inheritdoc}
     */
    protected function updateJobState(MaiaJob $job)
    {
        $job->state_id = State::trainingProposalsId();
        $job->save();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(MaiaJob $job)
    {
        $job->user->notify(new NoveltyDetectionComplete($job));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPatchStorageDisk()
    {
        return config('maia.training_proposal_storage_disk');
    }

    /**
     * {@inheritdoc}
     */
    protected function createMaiaAnnotations()
    {
        // Limit the number of annotations here again, because we don't "trust" the
        // settings of MAIA on the GPU server (in NoveltyDetectionRequest). If the GPU
        // server limits the annotations correctly, it's fine to reduce bandwidth. If
        // not, we do it here to make absolutely sure the limit is applied.
        $limit = config('maia.training_proposal_limit');
        $this->annotations = $this->maybeLimitAnnotations($this->annotations, $limit);

        return parent::createMaiaAnnotations();
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

    /**
     * Dispatches the job to generate annotation feature vectors for the MAIA annotations.
     *
     * @param MaiaJob $job
     */
    protected function dispatchAnnotationFeatureVectorsJob(MaiaJob $job)
    {
        Queue::push(new GenerateTrainingProposalFeatureVectors($job));
    }
}
