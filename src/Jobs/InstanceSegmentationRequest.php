<?php

namespace Biigle\Modules\Maia\Jobs;

use Exception;
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
        $this->trainingProposals = $job->trainingProposals()
            ->select('image_id', 'points')
            ->get()
            ->toArray();
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        // TODO Run actual instance segmentation here.
        if ($this->images->isNotEmpty()) {
            $id = $this->images->keys()->first();
            $this->dispatchResponse([$id => [
                [200, 200, 100, 0],
                [400, 400, 100, 0],
                [200, 400, 100, 0],
                [400, 200, 100, 0],
            ]]);
        } else {
            $this->dispatchResponse([]);
        }
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
    protected function cleanup()
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    protected function dispatchFailure(Exception $e)
    {
        $this->dispatch(new InstanceSegmentationFailure($this->jobId, $e));
    }
}
