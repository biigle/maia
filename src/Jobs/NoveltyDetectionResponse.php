<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Shape;
use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaAnnotation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\MaiaAnnotationType as Type;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;

/**
 * This job is executed on the machine running BIIGLE to store the results of novelty
 * detection.
 */
class NoveltyDetectionResponse extends Job implements ShouldQueue
{
    /**
     * ID of the MAIA job.
     *
     * @var int
     */
    protected $jobId;

    /**
     * Center points, radii and novelty scores of training proposals, indexed by image
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
    protected $trainingProposals;

    /**
     * Create a new instance
     *
     * @param int $jobId
     * @param array $trainingProposals
     */
    public function __construct($jobId, $trainingProposals)
    {
        $this->jobId = $jobId;
        $this->trainingProposals = $trainingProposals;
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        // TODO handle failure with database transaction. Move job params to "attrs".
        // Use attrs for novelty detection params, instance segmentation params and
        // failure error message. Add job state "failed".
        $job = MaiaJob::find($this->jobId);
        $this->createMaiaAnnotations();
        $this->dispatchAnnotationPatchJobs($job);
        $job->state_id = State::trainingProposalsId();
        $job->save();
    }

    /**
     * Create MAIA annotations from the training proposals.
     */
    protected function createMaiaAnnotations()
    {
        $maiaAnnotations = collect([]);

        foreach ($this->trainingProposals as $imageId => $proposals) {
            foreach ($proposals as $proposal) {
                $maiaAnnotations->push($this->createMaiaAnnotation($imageId, $proposal));
            }
        }

        $maiaAnnotations->chunk(9000)->each(function ($chunk) {
            // Chunk the insert because PDO's maximum number of query parameters is
            // 65535. Each annotation has 7 parameters so we can store roughly 9000
            // annotations in one call.
            MaiaAnnotation::insert($chunk->toArray());
        });
    }

    /**
     * Create an insert array for a MAIA annotation from a training proposal.
     *
     * @param int $imageId
     * @param array $proposal
     *
     * @return array
     */
    protected function createMaiaAnnotation($imageId, $proposal)
    {
        $points = array_map(function ($coordinate) {
            return round($coordinate, 2);
        }, [$proposal[0], $proposal[1], $proposal[2]]);

        return [
            'job_id' => $this->jobId,
            'points' => json_encode($points),
            'score' => $proposal[3],
            'selected' => false,
            'image_id' => $imageId,
            'shape_id' => Shape::circleId(),
            'type_id' => Type::trainingProposalId(),
        ];
    }

    /**
     * Dispatches the jobs to generate annotation patches for the MAIA annotations.
     *
     * @param MaiaJob $job
     */
    protected function dispatchAnnotationPatchJobs(MaiaJob $job)
    {
        $job->annotations()->trainingProposals()->chunk(1000, function ($chunk) {
            foreach ($chunk as $annotation) {
                GenerateAnnotationPatch::dispatch($annotation, $annotation->getPatchPath());
            }
        });
    }
}
