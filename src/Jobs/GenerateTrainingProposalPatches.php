<?php

namespace Biigle\Modules\Maia\Jobs;

class GenerateTrainingProposalPatches extends GenerateAnnotationPatches
{
    /**
     * Get a query for the annotations that have been created by this job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function getCreatedAnnotations()
    {
        return $this->job->trainingProposals();
    }

    /**
     * Get the storage disk to store the annotation patches to.
     */
    protected function getPatchStorageDisk()
    {
        return config('maia.training_proposal_storage_disk');
    }
}
