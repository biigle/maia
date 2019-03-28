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
     * Specifies whether the Mask R-CNN model has been stored.
     *
     * @var bool
     */
    public $storedModel;

    /**
     * Create a new instance
     *
     * @param int $jobId
     * @param array $annotations
     * @param bool $storedModel
     */
    public function __construct($jobId, $annotations, $storedModel)
    {
        parent::__construct($jobId, $annotations);
        $this->storedModel = $storedModel;
    }

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
        $job->has_model = $this->storedModel;
        $job->save();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(MaiaJob $job)
    {
        $job->user->notify(new InstanceSegmentationComplete($job));
    }

    /**
     * {@inheritdoc}
     */
    protected function getPatchStorageDisk()
    {
        return config('maia.annotation_candidate_storage_disk');
    }
}
