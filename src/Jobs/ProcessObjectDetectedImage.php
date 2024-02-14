<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Largo\Jobs\ProcessAnnotatedImage;
use Biigle\Modules\Maia\AnnotationCandidate;
use Biigle\Modules\Maia\AnnotationCandidateFeatureVector;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\VolumeFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProcessObjectDetectedImage extends ProcessAnnotatedImage
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
            skipSvgs: true,
            targetDisk: $targetDisk
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationQuery(VolumeFile $file): Builder
    {
        return AnnotationCandidate::where('image_id', $file->id)
            ->where('job_id', $this->maiaJob->id)
            ->when(!empty($this->only), fn ($q) => $q->whereIn('id', $this->only));
    }

    /**
     * Create the feature vectors based on the Python script output.
     */
    protected function updateOrCreateFeatureVectors(Collection $annotations, \Generator $output): void
    {
        $annotations = $annotations->keyBy('id');
        foreach ($output as $row) {
            $annotation = $annotations->get($row[0]);
            AnnotationCandidateFeatureVector::updateOrCreate(
                ['id' => $annotation->id],
                [
                    'job_id' => $annotation->job_id,
                    'vector' => $row[1],
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function redispatch(): void
    {
        static::dispatch(
                $this->file,
                $this->maiaJob,
                $this->only,
                $this->skipFeatureVectors,
                $this->targetDisk
            )
            ->onConnection($this->connection)
            ->onQueue($this->queue)
            ->delay(60);
    }
}
