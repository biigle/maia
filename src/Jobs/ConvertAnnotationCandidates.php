<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\AnnotationGuideline;
use Biigle\ImageAnnotation;
use Biigle\ImageAnnotationLabel;
use Biigle\Jobs\Job;
use Biigle\Jobs\ProcessAnnotatedImage;
use Biigle\Modules\Maia\Events\AnnotationCandidatesConverted;
use Biigle\Modules\Maia\Events\ConvertAnnotationCandidatesFailed;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\User;
use Carbon\Carbon;
use DB;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Throwable;

#[DeleteWhenMissingModels]
class ConvertAnnotationCandidates extends Job
{
    use SerializesModels;

    /**
     * The job to convert candidates of.
     *
     * @var MaiaJob
     */
    public $job;

    /**
     * The user who initiated the job.
     *
     * @var User
     */
    public $user;

    /**
     * Number of times to retry this job.
     *
     * @var integer
     */
    public $tries = 1;

    /**
     * Newly created annotations.
     *
     * @var Collection
     */
    protected $newAnnotations;

    /**
     * The enforced annotation guideline that applies to the converted candidates.
     */
    protected ?AnnotationGuideline $guideline = null;

    /**
     * Whether candidates were not converted because of the annotation guideline.
     */
    protected bool $hasUnconverted = false;

    /**
     * Create a new isntance.
     *
     * @param MaiaJob $job
     * @param User $user
     * @param int|null $guidelineId ID of the enforced annotation guideline that applies to the converted candidates.
     */
    public function __construct(MaiaJob $job, User $user, public ?int $guidelineId = null)
    {
        $this->queue = config('maia.convert_annotations_queue');
        $this->job = $job;
        $this->user = $user;
        $this->newAnnotations = collect([]);
    }

    /**
      * Handle the job.
      *
      * @return void
      */
    public function handle()
    {
        if (!is_null($this->guidelineId)) {
            // If the guideline was deleted or is no longer enforced in the meantime,
            // nothing is restricted.
            $this->guideline = AnnotationGuideline::where('enforced', true)->find($this->guidelineId);
        }

        try {
            DB::transaction(function () {
                $this->job->annotationCandidates()
                    ->whereNull('annotation_id')
                    ->whereNotNull('label_id')
                    ->chunkById(10000, [$this, 'processChunk']);
            });

            $this->newAnnotations
                ->groupBy('image_id')
                ->each(function ($group) {
                    $image = $group[0]->image;
                    $ids = $group->pluck('id')->all();
                    ProcessAnnotatedImage::dispatch($image, only: $ids);
                });

        } finally {
            $this->job->convertingCandidates = false;
            $this->job->save();
        }

        AnnotationCandidatesConverted::dispatch($this->job->id, $this->user, $this->hasUnconverted);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        ConvertAnnotationCandidatesFailed::dispatch($this->job->id, $this->user);
    }

    /**
     * Process a chunk of annotation candidates.
     *
     * @param \Illuminate\Support\Collection $candidates
     */
    public function processChunk($candidates)
    {
        $now = Carbon::now();
        $annotationLabels = [];

        foreach ($candidates as $candidate) {
            if (!$this->allowedByGuideline($candidate)) {
                // The candidate stays unconverted so the user can choose another label.
                $this->hasUnconverted = true;
                continue;
            }

            $annotation = new ImageAnnotation;
            $annotation->image_id = $candidate->image_id;
            $annotation->shape = $candidate->shape;
            $annotation->points = $candidate->points;
            $annotation->save();
            $this->newAnnotations[$candidate->id] = $annotation;

            $candidate->annotation_id = $annotation->id;
            $candidate->save();

            $annotationLabels[] = [
                'annotation_id' => $annotation->id,
                'label_id' => $candidate->label_id,
                'user_id' => $this->user->id,
                'confidence' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        ImageAnnotationLabel::insert($annotationLabels);
    }

    /**
     * Determine if the label and shape of the candidate are allowed by the annotation
     * guideline.
     */
    protected function allowedByGuideline($candidate): bool
    {
        if (is_null($this->guideline)) {
            return true;
        }

        return $this->guideline->allowsLabel($candidate->label_id)
            && $this->guideline->allowsShape($candidate->shape, $candidate->label_id);
    }
}
