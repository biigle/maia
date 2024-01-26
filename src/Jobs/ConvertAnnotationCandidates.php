<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\ImageAnnotation;
use Biigle\ImageAnnotationLabel;
use Biigle\Jobs\Job;
use Biigle\Modules\Largo\Jobs\ProcessAnnotatedImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\User;
use Carbon\Carbon;
use DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

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
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

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
     * Create a new isntance.
     *
     * @param MaiaJob $job
     * @param User $user
     */
    public function __construct(MaiaJob $job, User $user)
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
                    ProcessAnnotatedImage::dispatch($image, only: $ids)
                        ->onQueue(config('largo.generate_annotation_patch_queue'));
                });

        } finally {
            $this->job->convertingCandidates = false;
            $this->job->save();
        }
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
            $annotation = new ImageAnnotation;
            $annotation->image_id = $candidate->image_id;
            $annotation->shape_id = $candidate->shape_id;
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
}
