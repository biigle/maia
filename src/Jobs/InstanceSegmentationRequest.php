<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;

/**
 * This job is executed on a machine with GPU access.
 */
class InstanceSegmentationRequest extends Job implements ShouldQueue
{
    /**
     * ID of the MAIA job.
     *
     * @var int
     */
    protected $jobId;

    /**
     * Parameters of the MAIA job.
     *
     * @var array
     */
    protected $jobParams;

    /**
     * URL of the volume associated with the job.
     *
     * @var string
     */
    protected $volumeUrl;

    /**
     * Filenames of the images associated with the job, indexed by their IDs.
     *
     * @var array
     */
    protected $images;

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
        $this->jobId = $job->id;
        $this->jobParams = $job->params;
        $this->volumeUrl = $job->volume->url;
        $this->images = $job->volume->images()->pluck('filename', 'id');
        $this->trainingProposals = $job->trainingProposals()
            ->select('image_id', 'points')
            ->get();
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
        $response = new InstanceSegmentationResponse($this->jobId, $annotations);
        Queue::connection(config('maia.response_connection'))
            ->pushOn(config('maia.response_queue'), $response);
    }
}
