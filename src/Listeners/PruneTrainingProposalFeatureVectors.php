<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Illuminate\Contracts\Queue\ShouldQueue;

class PruneTrainingProposalFeatureVectors implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobContinued  $event
      * @return void
      */
    public function handle(MaiaJobContinued $event)
    {
        $event->job
            ->trainingProposals()
            ->unselected()
            ->select('id')
            ->chunkById(1000, function ($chunk) {
                TrainingProposalFeatureVector::whereIn('id', $chunk->pluck('id'))
                    ->delete();
            });
    }
}
