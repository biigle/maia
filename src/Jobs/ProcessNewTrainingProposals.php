<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNewTrainingProposals extends Job implements ShouldQueue
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
                    ->from('maia_training_proposals')
                    ->where('maia_training_proposals.job_id', $this->maiaJob->id)
                    ->whereColumn('maia_training_proposals.image_id', 'images.id')
            )
            ->eachById(fn ($image) =>
                ProcessNoveltyDetectedImage::dispatch($image, $this->maiaJob,
                        targetDisk: config('maia.training_proposal_storage_disk')
                    )
                    ->onQueue(config('largo.generate_annotation_patch_queue'))
            );
    }
}
