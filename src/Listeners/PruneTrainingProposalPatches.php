<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\DeleteAnnotationPatchChunk;
use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;
use Storage;

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
        $disk = config('maia.training_proposal_storage_disk');
        $format = config('largo.patch_format');

        $event->job->trainingProposals()
            ->unselected()
            ->with('image')
            ->chunkById(100, function ($chunk) use ($disk, $format) {
                $files = [];
                foreach ($chunk as $proposal) {
                    $prefix = fragment_uuid_path($proposal->image->uuid);
                    $files[] = "{$prefix}/{$proposal->id}.{$format}";
                }
                Queue::push(new DeleteAnnotationPatchChunk($disk, $files));
            });
    }
}
