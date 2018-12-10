<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Exception;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationComplete;

class InstanceSegmentationResponse extends JobResponse
{
    /**
     * {@inheritdoc}
     */
    protected function getExpectedJobStateId()
    {
        return State::instanceSegmentationId();
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Queue::push(new InstanceSegmentationFailure($this->jobId, $exception));
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
    protected function getCreatedAnnotations(MaiaJob $job)
    {
        return $job->annotationCandidates();
    }

    /**
     * {@inheritdoc}
     */
    protected function updateJobState(MaiaJob $job)
    {
        $job->state_id = State::annotationCandidatesId();
        $job->save();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(MaiaJob $job)
    {
        $job->user->notify(new InstanceSegmentationComplete($job));
    }
}
