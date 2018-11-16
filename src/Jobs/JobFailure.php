<?php

namespace Biigle\Modules\Maia\Jobs;

use Exception;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * This job is executed on the machine running BIIGLE to store the error state of a
 * failed novelty detection or instance segmentation.
 */
class JobFailure extends Job implements ShouldQueue
{
    /**
     * ID of the MAIA job.
     *
     * @var int
     */
    protected $jobId;

    /**
     * Error message.
     *
     * @var string
     */
    protected $message;

    /**
     * Create a new instance
     *
     * @param int $jobId
     * @param Exception $exception
     */
    public function __construct($jobId, Exception $exception)
    {
        $this->jobId = $jobId;
        $this->message = $exception->getMessage();
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        $job = MaiaJob::find($this->jobId);
        $job->error = ['message' => $this->message];
        $this->updateJobState($job);
        $job->save();
    }

    /**
     * Set the job to a failed state.
     *
     * @param MaiaJob $job
     */
    protected function updateJobState(MaiaJob $job)
    {
        //
    }
}
