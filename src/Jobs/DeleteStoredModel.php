<?php

namespace Biigle\Modules\Maia\Jobs;

use Storage;
use Biigle\Modules\Maia\MaiaJob;

class DeleteStoredModel extends Job
{
    /**
     * The MAIA job ID.
     *
     * @var int
     */
    protected $jobId;

    /**
     * Create a new isntance.
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        $this->jobId = $job->id;
    }

   /**
     * Handle the event.
     */
    public function handle()
    {
        Storage::disk(config('maia.model_storage_disk'))->delete($this->jobId);
    }
}
