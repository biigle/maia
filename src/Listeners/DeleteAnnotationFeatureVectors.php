<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\Events\MaiaJobDeleting;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;

class DeleteAnnotationFeatureVectors
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobDeleting  $event
      * @return void
      */
    public function handle(MaiaJobDeleting $event)
    {
        TrainingProposalFeatureVector::where('job_id', $event->job->id)->delete();
        AnnotationCandidateFeatureVector::where('job_id', $event->job->id)->delete();
    }
}
