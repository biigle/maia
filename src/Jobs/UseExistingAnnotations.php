<?php

namespace Biigle\Modules\Maia\Jobs;

use DB;
use Arr;
use Biigle\Shape;
use Biigle\Jobs\Job;
use Biigle\Annotation;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Queue\SerializesModels;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Largo\Jobs\GenerateAnnotationPatch;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionFailed;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;

class UseExistingAnnotations extends Job
{
    use SerializesModels;

    /**
     * The job to use existing annotations for.
     *
     * @var MaiaJob
     */
    protected $job;

    /**
     * Create a new isntance.
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        $this->job = $job;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->job->shouldSkipNoveltyDetection() && !$this->hasAnnotations()) {
            $this->job->error = ['message' => 'Novelty detection should be skipped but there are no existing annotations to take as training proposals.'];
            $this->job->state_id = State::failedNoveltyDetectionId();
            $this->job->save();
            $this->job->user->notify(new NoveltyDetectionFailed($this->job));

            return;
        }

        $this->convertAnnotations();
        $this->dispatchAnnotationPatchJobs();

        if ($this->job->shouldSkipNoveltyDetection()) {
            $this->job->state_id = State::trainingProposalsId();
            $this->job->save();
            $this->job->user->notify(new NoveltyDetectionComplete($this->job));
        }
    }

    /**
     * Get the query for the annotations to convert.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getAnnotationsQuery()
    {
        $restrictLabels = Arr::get($this->job->params, 'restrict_labels', []);

        return Annotation::join('images', 'annotations.image_id', '=', 'images.id')
            ->where('images.volume_id', $this->job->volume_id)
            ->when(!empty($restrictLabels), function ($query) use ($restrictLabels) {
                return $query->join('annotation_labels', 'annotation_labels.annotation_id', '=', 'annotations.id')
                    ->whereIn('annotation_labels.label_id', $restrictLabels);
            });
    }

    /**
     * Determine if there are any annotations to convert.
     *
     * @return  bool
     */
    protected function hasAnnotations()
    {
        return $this->getAnnotationsQuery()->exists();
    }

    /**
     * Convert all matching annotations to training proposals.
     */
    protected function convertAnnotations()
    {
        $this->getAnnotationsQuery()
            // Use DISTINCT ON to get only one result per annotation, no matter how many
            // matching labels are attached to it. We can't simply use DISTINCT because
            // the rows include JSON.
            ->select(DB::raw('DISTINCT ON (annotations_id) annotations.id as annotations_id, annotations.points, annotations.image_id, annotations.shape_id'))
            ->chunkById(1000, [$this, 'convertAnnotationChunk'], 'annotations.id', 'annotations_id');
    }

    /**
     * Convert a chunk of annotations to training proposals.
     *
     * Must be public to be used as callable.
     *
     * @param \Illuminate\Support\Collection $chunk
     */
    public function convertAnnotationChunk($chunk)
    {
        $trainingProposals = $chunk->map(function ($annotation) {
            return [
                'points' => $this->convertAnnotationPoints($annotation),
                'image_id' => $annotation->image_id,
                'shape_id' => Shape::circleId(),
                'job_id' => $this->job->id,
                // score should be null in this case.
            ];
        });

        TrainingProposal::insert($trainingProposals->toArray());
    }

    /**
     * Convert the points of an annotation to the points of a circle.
     *
     * @param Annotation $annotation
     *
     * @return string JSON encoded points array.
     */
    protected function convertAnnotationPoints(Annotation $annotation)
    {
        if ($annotation->shape_id === Shape::pointId()) {
            // Points are converted to circles with a default radius of 50 px.
            $points = [$annotation->points[0], $annotation->points[1], 50];
        } elseif ($annotation->shape_id === Shape::circleId()) {
            $points = $annotation->points;
        } else {
            $points = $this->convertPolygonToCirlce($annotation->points);
        }

        return json_encode($points);
    }

    /**
     * Determine the points of a circle from the points of a polygon.
     *
     * @param array $points
     *
     * @return array
     */
    protected function convertPolygonToCirlce($points)
    {
        $count = count($points);
        $minX = INF;
        $minY = INF;
        $maxX = -INF;
        $maxY = -INF;

        for ($i = 0; $i < $count; $i += 2) {
            $minX = min($minX, $points[$i]);
            $minY = min($minY, $points[$i + 1]);
            $maxX = max($maxX, $points[$i]);
            $maxY = max($maxY, $points[$i + 1]);
        }

        // [$x, $y] is the center point of the bounding rectangle.
        $x = round(($minX + $maxX) / 2, 2);
        $y = round(($minY + $maxY) / 2, 2);

        // $r should be the maximum distance between the center point and any point of
        // the polygon.
        $r = -INF;
        for ($i = 0; $i < $count; $i += 2) {
            $r = max($r, ($x - $points[$i])**2 + ($y - $points[$i + 1])**2);
        }
        $r = round(sqrt($r), 2);

        return [$x, $y, $r];
    }

    /**
     * Dispatch the jobs to generate image patches for the new training proposals.
     */
    protected function dispatchAnnotationPatchJobs()
    {
        $disk = config('maia.training_proposal_storage_disk');
        $this->job->trainingProposals()->chunk(1000, function ($chunk) use ($disk) {
            $chunk->each(function ($proposal) use ($disk) {
                GenerateAnnotationPatch::dispatch($proposal, $disk);
            });
        });
    }
}
