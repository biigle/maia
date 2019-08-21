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
     * Create the temporary directory for this request.
     */
    protected function createTmpDir()
    {
        if (!isset($this->tmpDir)) {
            // Do not set this in the constructor because else the config of the
            // requesting instance would be used and not the config of the GPU instance.
            $this->tmpDir = $this->getTmpDirPath();
            $this->ensureDirectory($this->tmpDir);
        }
    }

    /**
     * Creates the specified directory if it does not exist.
     *
     * @param string $path
     */
    protected function ensureDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0700, true, true);
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


    /**
     * Parse the output JSON files to get the array of annotations for each image.
     *
     * @param array $images GenericImage instances.
     *
     * @return array
     */
    protected function parseAnnotations($images)
    {
        $annotations = [];
        $isNull = 0;

        foreach ($images as $image) {
            $newAnnotations = $this->parseAnnotationsFile($image);
            if (is_null($newAnnotations)) {
                $isNull += 1;
            } else {
                $annotations = array_merge($annotations, $newAnnotations);
            }
        }

        // For many images (as it is common) it might be not too bad if novelty detection
        // failed for some of them. We still get enough training proposals and don't want
        // to execute the long running novelty detection again. But if too many images
        // failed, abort.
        if (($isNull / count($images)) > 0.1) {
            throw new Exception('Unable to parse more than 10 % of the output JSON files.');
        }

        return $annotations;
    }

    /**
     * Parse the output JSON file of a single image.
     *
     * @param GenericImage $image
     *
     * @return array
     */
    protected function parseAnnotationsFile($image)
    {
        $path = "{$this->tmpDir}/{$image->getId()}.json";

        // This might happen for corrupt image files which are skipped.
        if (!File::exists($path)) {
            return [];
        }

        $annotations = json_decode(File::get($path), true);

        if (is_array($annotations)) {
            foreach ($annotations as &$annotation) {
                array_unshift($annotation, $image->getId());
            }
        }

        // Each annotation is an array:
        // [$imageId, $xCenter, $yCenter, $radius, $score]
        return $annotations;
    }
}
