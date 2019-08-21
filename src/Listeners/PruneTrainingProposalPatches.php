<?php

namespace Biigle\Modules\Maia\Listeners;

use Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Events\MaiaJobContinued;

class PruneTrainingProposalPatches implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobContinued  $event
      * @return void
      */
    public function handle(MaiaJobContinued $event)
    {
        $disk = Storage::disk(config('maia.training_proposal_storage_disk'));
        $format = config('largo.patch_format');

        $event->job->trainingProposals()
            ->unselected()
            ->with('image')
            ->chunkById(1000, function ($chunk) use ($disk, $format) {
                foreach ($chunk as $proposal) {
                    $prefix = fragment_uuid_path($proposal->image->uuid);
                    $disk->delete("{$prefix}/{$proposal->id}.{$format}");
                }
            });
    }
}
