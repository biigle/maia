<?php

namespace Biigle\Modules\Maia\Jobs;

use DB;
use Biigle\Shape;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaAnnotation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;

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
     * Center points, radii and scores of annotations to create, indexed by image
     * ID. Example:
     * [
     *     image_id => [
     *         [center_x, center_y, radius, score],
     *         [center_x, center_y, radius, score],
     *         ...
     *     ],
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
            $this->dispatchAnnotationPatchJobs($job);
            $this->updateJobState($job);
        });

        $this->sendNotification($job);
    }

    /**
     * Create MAIA annotations from the training proposals.
     */
    protected function createMaiaAnnotations()
    {
        $maiaAnnotations = collect([]);

        foreach ($this->annotations as $imageId => $proposals) {
            foreach ($proposals as $proposal) {
                $maiaAnnotations->push($this->createMaiaAnnotation($imageId, $proposal));
            }
        }

        $maiaAnnotations->chunk(9000)->each(function ($chunk) {
            // Chunk the insert because PDO's maximum number of query parameters is
            // 65535. Each annotation has 7 parameters so we can store roughly 9000
            // annotations in one call.
            $this->insertAnnotationChunk($chunk->toArray());
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
     * @param int $imageId
     * @param array $annotation
     *
     * @return array
     */
    protected function createMaiaAnnotation($imageId, $annotation)
    {
        $points = array_map(function ($coordinate) {
            return round($coordinate, 2);
        }, [$annotation[0], $annotation[1], $annotation[2]]);

        return [
            'job_id' => $this->jobId,
            'points' => json_encode($points),
            'score' => $annotation[3],
            'image_id' => $imageId,
            'shape_id' => Shape::circleId(),
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
        $this->getCreatedAnnotations($job)->chunk(1000, function ($chunk) {
            foreach ($chunk as $annotation) {
                GenerateAnnotationPatch::dispatch($annotation, $annotation->getPatchPath());
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
}
