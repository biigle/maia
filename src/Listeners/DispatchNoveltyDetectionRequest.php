<?php

namespace Biigle\Modules\Maia\Listeners;

use Queue;
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
        $request = new NoveltyDetectionRequest($event->job);
        Queue::connection(config('maia.request_connection'))
            ->push($request, '', config('maia.request_queue'));
    }
}
