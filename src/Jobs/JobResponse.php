<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Shape;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * This job is executed on the machine running BIIGLE to store the results of novelty
 * detection or instance segmentation.
 */
class JobResponse extends Job implements ShouldQueue
{
    /**
     * ID of the MAIA job.
     *
     * @var int
     */
    public $jobId;

    /**
     * Image ID, center points, radii and scores of annotations to create.
     * Example:
     * [
     *     [image_id, center_x, center_y, radius, score],
     *     [image_id, center_x, center_y, radius, score],
     *     ...
     * ]
     *
     * @var array
     */
    public $annotations;

    /**
     * Create a new instance
     *
     * @param int $jobId
     * @param array $annotations
     */
    public function __construct($jobId, $annotations)
    {
        $this->jobId = $jobId;
        $this->annotations = $annotations;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        $job = MaiaJob::where('state_id', $this->getExpectedJobStateId())
            ->find($this->jobId);
        if ($job === null) {
            // Ignore the results if the job no longer exists for some reason.
            return;
        }

        // Make sure to roll back any DB modifications if an error occurs.
        DB::transaction(function () use ($job) {
            $this->createMaiaAnnotations();
            $this->updateJobState($job);
        });

        $this->dispatchAnnotationPatchJobs($job);
        $this->sendNotification($job);
    }

    /**
     * Create MAIA annotations from the training proposals.
     */
    protected function createMaiaAnnotations()
    {
        $maiaAnnotations = array_map(function ($annotation) {
            return $this->createMaiaAnnotation($annotation);
        }, $this->annotations);

        // Chunk the insert because PDO's maximum number of query parameters is
        // 65535. Each annotation has 7 parameters so we can store roughly 9000
        // annotations in one call.
        $maiaAnnotations = array_chunk($maiaAnnotations, 9000);
        array_walk($maiaAnnotations, function ($chunk) {
            $this->insertAnnotationChunk($chunk);
        });
    }

    /**
     * Get the job state ID that the job is required to have to be modified.
     *
     * @return int
     */
    protected function getExpectedJobStateId()
    {
        return null;
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

        $label_id = isset($annotation[4]) ? $annotation[4] : NULL;

        return [
            'job_id' => $this->jobId,
            'points' => json_encode($points),
            'score' => $annotation[4],
            'image_id' => $annotation[0],
            'shape_id' => Shape::circleId(),
            'label_id' => $label_id
        ];
    }

    /**
     * Insert one chunk of the MAIA annotations that should be created into the database.
     *
     * @param array $chunk
     */
    protected function insertAnnotationChunk(array $chunk)
    {
        //
    }

    /**
     * Dispatches the jobs to generate annotation patches for the MAIA annotations.
     *
     * @param MaiaJob $job
     */
    protected function dispatchAnnotationPatchJobs(MaiaJob $job)
    {
        $disk = $this->getPatchStorageDisk();
        $this->getCreatedAnnotations($job)->chunkById(1000, function ($chunk) use ($disk) {
            foreach ($chunk as $annotation) {
                GenerateImageAnnotationPatch::dispatch($annotation, $disk)
                    ->onQueue(config('largo.generate_annotation_patch_queue'));
            }
        });
    }

    /**
     * Get a query for the annotations that have been created by this job.
     *
     * @param MaiaJob $job
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function getCreatedAnnotations(MaiaJob $job)
    {
        return $job->annotations();
    }

    /**
     * Update the state of the MAIA job after processing the response.
     *
     * @param MaiaJob $job
     */
    protected function updateJobState(MaiaJob $job)
    {
        //
    }

    /**
     * Send the notification about the completion to the creator of the job.
     *
     * @param MaiaJob $job
     */
    protected function sendNotification(MaiaJob $job)
    {
        //
    }

    /**
     * Get the storage disk to store the annotation patches to.
     */
    protected function getPatchStorageDisk()
    {
        //
    }
}
