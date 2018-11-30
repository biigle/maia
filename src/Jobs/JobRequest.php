<?php

namespace Biigle\Modules\Maia\Jobs;

use File;
use Queue;
use Exception;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\GenericImage;
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
     * Temporary directory for files of this job.
     *
     * @var string
     */
    protected $tmpDir;

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
        $this->images = $job->volume->images()->pluck('filename', 'id')->toArray();
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
     * Create GenericImage instances for the images of this job.
     *
     * @return array
     */
    protected function getGenericImages()
    {
        $images = [];
        foreach ($this->images as $id => $filename) {
            $images[$id] = new GenericImage($id, "{$this->volumeUrl}/{$filename}");
        }

        return $images;
    }

    /**
     * Clean up temporary data produced by this job.
     */
    protected function cleanup()
    {
        File::deleteDirectory($this->tmpDir);
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
     * Make sure the temporary directory for this request exists.
     */
    protected function ensureTmpDir()
    {
        if (!isset($this->tmpDir)) {
            // Do not set this in the constructor because else the config of the
            // requesting instance would be used and not the config of the GPU instance.
            $this->tmpDir = $this->getTmpDirPath();
            File::makeDirectory($this->tmpDir, 0700, true, true);
        }
    }

    /**
     * Get the path to the temporary directory.
     *
     * @return string
     */
    protected function getTmpDirPath()
    {
        return config('maia.tmp_dir')."/maia-{$this->jobId}";
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

    /**
     * Execute a Python command and return the path to a file containing the output.
     *
     * @param string $command
     * @param string $log Name of the log file to write any output to.
     * @throws Exception On a non-zero exit code.
     *
     * @return string
     */
    protected function python($command, $log = 'log.txt')
    {
        $code = 0;
        $lines = [];
        $python = config('maia.python');
        $logFile = "{$this->tmpDir}/{$log}";
        exec("{$python} -u {$command} >{$logFile} 2>&1", $lines, $code);

        if ($code !== 0) {
            $lines = File::get($logFile);
            throw new Exception("Error while executing python command '{$command}':\n{$lines}", $code);

        }

        return $logFile;
    }
}
