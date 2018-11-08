<?php

namespace Biigle\Modules\Maia\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;

class DispatchNoveltyDetectionRequest implements ShouldQueue
{
   /**
     * Handle the event.
     *
     * @param  MaiaJobCreated  $event
     * @return void
     */
    public function handle(MaiaJobCreated $event)
    {
        NoveltyDetectionRequest::dispatch($event->job)
            ->onQueue(config('maia.request_queue'))
            ->onConnection(config('maia.request_connection'));
    }
}
