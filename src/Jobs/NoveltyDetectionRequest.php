<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * This job is executed on a maching with GPU access.
 */
class NoveltyDetectionRequest extends Job implements ShouldQueue
{
    /**
     * Create a new instance
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        //
    }

    /**
     * Execute the job
     */
    public function handle()
    {
        //
    }
}
