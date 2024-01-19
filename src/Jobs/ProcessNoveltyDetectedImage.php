<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\ProcessAnnotatedImage;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Modules\Maia\TrainingProposalFeatureVector;
use Biigle\VolumeFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProcessNoveltyDetectedImage extends ProcessAnnotatedImage
{
    public function __construct(
        public VolumeFile $file,
        public MaiaJob $maiaJob,
        public array $only = [],
        public bool $skipFeatureVectors = false,
        public ?string $targetDisk = null
    )
    {
        parent::__construct($file, $only,
            skipFeatureVectors: $skipFeatureVectors,
            targetDisk: $targetDisk
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationQuery(VolumeFile $file): Builder
    {
        return TrainingProposal::where('image_id', $file->id)
            ->where('job_id', $this->maiaJob->id);
    }

    /**
     * Create the feature vectors based on the Python script output.
     */
    protected function updateOrCreateFeatureVectors(Collection $annotations, \Generator $output): void
    {
        $annotations = $annotations->keyBy('id');
        foreach ($output as $row) {
            $annotation = $annotations->get($row[0]);
            TrainingProposalFeatureVector::updateOrCreate(
                ['id' => $annotation->id],
                [
                    'job_id' => $annotation->job_id,
                    'vector' => $row[1],
                ]
            );
        }
    }
}
