<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\SerializesModels;

#[DeleteWhenMissingModels]
class ProcessNewAnnotationCandidates extends Job implements ShouldQueue
{
    use SerializesModels;

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
                    targetDisk: config('maia.annotation_candidate_storage_disk')
                )
            );
    }
}
