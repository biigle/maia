<?php

namespace Biigle\Modules\Maia\Jobs;

class GenerateAnnotationCandidatePatches extends GenerateAnnotationPatches
{
    /**
     * Get a query for the annotations that have been created by this job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    protected function getCreatedAnnotations()
    {
        return $this->job->annotationCandidates();
    }

    /**
     * Get the storage disk to store the annotation patches to.
     */
    protected function getPatchStorageDisk()
    {
        return config('maia.annotation_candidate_storage_disk');
    }
}
