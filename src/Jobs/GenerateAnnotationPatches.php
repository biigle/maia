<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Queue\SerializesModels;
use Biigle\Modules\Largo\Jobs\GenerateImageAnnotationPatch;

abstract class GenerateAnnotationPatches extends Job
{
    use SerializesModels;

    /**
     * Number of feature vector models to insert in one chunk.
     *
     * @var int
     */
    const JOB_CHUNK_SIZE = 1000;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

    /**
     * Create a new isntance.
     */
    public function __construct(public MaiaJob $job)
    {
        //
    }

    /**
      * Handle the job.
      *
      * @return void
      */
    public function handle()
    {
        $disk = $this->getPatchStorageDisk();
        $this->getCreatedAnnotations()
            ->chunkById(self::JOB_CHUNK_SIZE, function ($chunk) use ($disk) {
                foreach ($chunk as $annotation) {
                    GenerateImageAnnotationPatch::dispatch($annotation, $disk)
                        ->onQueue(config('largo.generate_annotation_patch_queue'));
                }
            });
    }

    /**
     * Get a query for the annotations that have been created by this job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    abstract protected function getCreatedAnnotations();

    /**
     * Get the storage disk to store the annotation patches to.
     */
    abstract protected function getPatchStorageDisk();
}
