<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\GenericImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use Exception;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class DetectionJob implements ShouldQueue
{
    use Queueable;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

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
    public function __construct(public MaiaJob $job)
    {
        $this->onQueue(config('maia.job_queue'));
        $this->onConnection(config('maia.job_connection'));
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        if (!config('maia.debug_keep_files')) {
            $this->cleanup();
        }

        $this->dispatchFailure($exception);
    }

    /**
     * Create GenericImage instances for the images of this job.
     */
    protected function getGenericImages(): array
    {
        $volume = $this->job->volume;

        return $volume
            ->images()
            ->pluck('filename', 'id')
            ->mapWithKeys(fn ($filename, $id) =>
                    [$id => new GenericImage($id, "{$volume->url}/{$filename}")]
            )
            ->toArray();
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
    abstract protected function dispatchFailure(Exception $e);

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
        return config('maia.tmp_dir')."/maia-{$this->job->id}";
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

    /**
     * Create MAIA annotations from the training proposals.
     */
    protected function createMaiaAnnotations(array $annotations)
    {
        $maiaAnnotations = array_map(function ($annotation) {
            return $this->createMaiaAnnotation($annotation);
        }, $annotations);

        $existingIds = $this->job->volume->images()->pluck('id', 'id')->toArray();
        $maiaAnnotations = array_filter($maiaAnnotations, fn ($a) => array_key_exists($a['image_id'], $existingIds));

        // Chunk the insert because PDO's maximum number of query parameters is
        // 65535. Each annotation has 7 parameters so we can store roughly 9000
        // annotations in one call.
        $maiaAnnotations = array_chunk($maiaAnnotations, 9000);
        array_walk($maiaAnnotations, function ($chunk) {
            $this->insertAnnotationChunk($chunk);
        });
    }

    /**
     * Create an insert array for a MAIA annotation.
     *
     * @param array $annotation
     *
     * @return array
     */
    protected function createMaiaAnnotation($annotation)
    {
        $points = array_map(function ($coordinate) {
            return round($coordinate, 2);
        }, [$annotation[1], $annotation[2], $annotation[3]]);

        return [
            'job_id' => $this->job->id,
            'points' => json_encode($points),
            'score' => $annotation[4],
            'image_id' => $annotation[0],
            'shape_id' => Shape::circleId(),
        ];
    }

    /**
     * Insert one chunk of the MAIA annotations that should be created into the database.
     *
     * @param array $chunk
     */
    abstract protected function insertAnnotationChunk(array $chunk);

    /**
     * Update the state of the MAIA job after processing the response.
     */
    abstract protected function updateJobState();
}
