<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Exception;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;

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
}
