<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\ImageAnnotation;
use Biigle\ImageAnnotationLabel;
use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\User;
use Carbon\Carbon;
use DB;
use Illuminate\Queue\SerializesModels;

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
    }

    /**
      * Handle the job.
      *
      * @return void
      */
    public function handle()
    {
        try {
            $annotations = DB::transaction(function () {
                $candidates = $this->job->annotationCandidates()
                    ->whereNull('annotation_id')
                    ->whereNotNull('label_id')
                    ->get();

                $now = Carbon::now();
                $annotations = [];
                $annotationLabels = [];

                foreach ($candidates as $candidate) {
                    $annotation = new ImageAnnotation;
                    $annotation->image_id = $candidate->image_id;
                    $annotation->shape_id = $candidate->shape_id;
                    $annotation->points = $candidate->points;
                    $annotation->save();
                    $annotations[$candidate->id] = $annotation;

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

                return $annotations;
            });

            foreach ($annotations as $annotation) {
                GenerateImageAnnotationPatch::dispatch($annotation)
                    ->onQueue(config('largo.generate_annotation_patch_queue'));
            }
        } finally {
            $this->job->convertingCandidates = false;
            $this->job->save();
        }
    }
}
