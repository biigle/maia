<?php

namespace Biigle\Modules\Maia\Jobs;

use Queue;
use Exception;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * This job is executed on a machine with GPU access.
 */
class JobRequest extends Job implements ShouldQueue
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
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->cleanup();
        $this->dispatchFailure($exception);
    }

    /**
     * Clean up temporary data produced by this job.
     */
    protected function cleanup()
    {
        //
    }

    /**
     * Dispatch the job to notify the BIIGLE instance of a failure.
     *
     * @param Exception $e
     */
    protected function dispatchFailure(Exception $e)
    {
        //
    }

    /**
     * Dispatch a response (success or failure) to the BIIGLE instance.
     *
     * @param Job $job The job to be sent to the BIIGLE instance.
     */
    protected function dispatch(Job $job)
    {
        Queue::connection(config('maia.response_connection'))
            ->pushOn(config('maia.response_queue'), $job);
    }
}
