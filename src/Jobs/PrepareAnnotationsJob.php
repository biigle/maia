<?php

namespace Biigle\Modules\Maia\Jobs;

use Arr;
use Biigle\ImageAnnotation;
use Biigle\Jobs\Job;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationFailed;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\Traits\QueriesExistingAnnotations;
use Biigle\Shape;
use DB;
use Illuminate\Queue\SerializesModels;

abstract class PrepareAnnotationsJob extends Job
{
    use SerializesModels, QueriesExistingAnnotations;

    /**
     * The job to use existing annotations for.
     *
     * @var MaiaJob
     */
    protected $job;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

    /**
     * Set newly converted training proposals as selected.
     *
     * @var bool
     */
    protected $selectTrainingProposals = true;

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
     * Get the query for the annotations to convert.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    abstract protected function getAnnotationsQuery();

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
        DB::transaction(function () {
            $query = $this->getAnnotationsQuery();

            // Check is ImageAnnotationLabel Table is joined from $this->getAnnotationsQuery(). Since, there might be the case in which both restricted labels and ignoreExistingLabel might exist.

            $joins = collect($query->getQuery()->joins);
            $containsImageAnnotationLabels = $joins->pluck('table')->contains('image_annotation_labels');

            // Use DISTINCT ON to get only one result per annotation, no matter how
            // many matching labels are attached to it. We can't simply use DISTINCT
            // because the rows include JSON.

            $query->when($containsImageAnnotationLabels, function($query, $containsImageAnnotationLabels){
                  return $query->select(DB::raw('DISTINCT ON (annotations_id) image_annotations.id as annotations_id, image_annotation_labels.id,image_annotations.points, image_annotations.image_id, image_annotations.shape_id, image_annotation_labels.label_id'))
                  ->orderByRaw('image_annotations.id ASC')
                  ->orderByRaw("image_annotation_labels.id ASC");
                }, function($query) {
                  return $query->select(DB::raw('DISTINCT ON (annotations_id) image_annotations.id as annotations_id, image_annotations.points, image_annotations.image_id, image_annotations.shape_id, Null as label_id'));
                })
                ->chunkById(1000, [$this, 'convertAnnotationChunk'], 'image_annotations.id', 'annotations_id');
        });
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
                'points' => $this->convertAnnotationPointsToCircle($annotation),
                'image_id' => $annotation->image_id,
                'shape_id' => Shape::circleId(),
                'label_id' => $annotation->label_id,
                'job_id' => $this->job->id,
                // All these proposals should be taken for instance segmentation unless
                // the user chose to review them as training proposals first.
                'selected' => $this->selectTrainingProposals,
                // score should be null in this case.
            ];
        });

        TrainingProposal::insert($trainingProposals->toArray());
    }

    /**
     * Convert the points of an annotation to the points of a circle.
     *
     * @param ImageAnnotation $annotation
     *
     * @return string JSON encoded points array.
     */
    protected function convertAnnotationPointsToCircle(ImageAnnotation $annotation)
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
}
