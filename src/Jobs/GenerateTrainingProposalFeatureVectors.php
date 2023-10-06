<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\TrainingProposalFeatureVector;

class GenerateTrainingProposalFeatureVectors extends GenerateAnnotationFeatureVectors
{
    /**
     * Get the training proposals or annotation candidates of the job.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAnnotations()
    {
        return $this->job->trainingProposals;
    }

    /**
     * Insert a chunk of new feature vector models.
     */
    protected function insertFeatureVectorModelChunk(array $chunk): void
    {
        TrainingProposalFeatureVector::insert($chunk);
    }
}
