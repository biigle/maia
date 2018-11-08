<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;

class MaiaJobCreated implements ShouldQueue
{
   /**
     * Handle the event.
     *
     * @param  MaiaJob  $job
     * @return void
     */
    public function handle(MaiaJob $job)
    {
        NoveltyDetectionRequest::dispatch($job)
            ->onQueue(config('maia.request_queue'))
            ->onConnection(config('maia.request_connection'));
    }
}
