<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;

/**
 * This job is executed on a maching with GPU access.
 */
class NoveltyDetectionRequest extends Job implements ShouldQueue
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
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        // TODO Run actual novelty detection here.
        if ($this->images->isNotEmpty()) {
            $id = $this->images->keys()->first();
            $this->dispatchResponse([$id => [
                [200, 200, 100, 0.5],
                [400, 400, 100, 0.7],
                [200, 400, 100, 0.1],
                [400, 200, 100, 1.0],
            ]]);
        } else {
            $this->dispatchResponse([]);
        }
    }

    /**
     * Dispatch the job to store the novelty detection results.
     *
     * @param array $trainingProposals
     */
    protected function dispatchResponse($trainingProposals)
    {
        $response = new NoveltyDetectionResponse($this->jobId, $trainingProposals);
        Queue::connection(config('maia.response_connection'))
            ->pushOn(config('maia.response_queue'), $response);
    }
}
