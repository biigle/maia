<?php

namespace Biigle\Modules\Maia\Listeners;

use File;
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
        $event->job->trainingProposals()->unselected()->chunk(1000, function ($chunk) {
            $chunk->each(function ($proposal) {
                File::delete($proposal->getPatchPath());
            });
        });
    }
}
