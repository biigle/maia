<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class GenerateAnnotationCandidatePatches extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

    /**
     * Create a new isntance.
     */
    public function __construct(public MaiaJob $maiaJob)
    {
        //
    }

    public function handle(): void
    {
        $this->maiaJob->volume->images()
            ->whereExists(fn ($q) =>
                $q->select(\DB::raw(1))
                    ->from('maia_annotation_candidates')
                    ->where('maia_annotation_candidates.job_id', $this->maiaJob->id)
                    ->whereColumn('maia_annotation_candidates.image_id', 'images.id')
            )
            ->eachById(fn ($image) =>
                ProcessObjectDetectedImage::dispatch($image, $this->maiaJob,
                        // Feature vectors are generated in a separate job on the GPU.
                        skipFeatureVectors: true,
                        targetDisk: config('maia.annotation_candidate_storage_disk')
                    )
                    ->onQueue(config('largo.generate_annotation_patch_queue'))
            );
    }
}
