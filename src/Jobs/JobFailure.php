<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\MaiaJob;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

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
        $this->sendNotification($job);
        Log::error("MAIA job {$job->id} failed!");
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

    /**
     * Send the notification about the failure to the creator of the job.
     *
     * @param MaiaJob $job
     */
    protected function sendNotification(MaiaJob $job)
    {
        //
    }
}
