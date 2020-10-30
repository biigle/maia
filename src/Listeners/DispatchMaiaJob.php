<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\PrepareExistingAnnotations;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;

class DispatchMaiaJob implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobCreated  $event
      * @return void
      */
    public function handle(MaiaJobCreated $event)
    {
        if ($event->job->shouldUseNoveltyDetection()) {
            $request = new NoveltyDetectionRequest($event->job);
            Queue::connection(config('maia.request_connection'))
                ->pushOn(config('maia.request_queue'), $request);
        } else if ($event->job->shouldUseExistingAnnotations()) {
            PrepareExistingAnnotations::dispatch($event->job);
        } else if ($event->job->shouldUseKnowledgeTransfer()) {
            PrepareKnowledgeTransfer::dispatch($event->job);
        } else {
            throw new Exception('Unknown training data method.');
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  MaiaJobCreated  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(MaiaJobCreated $event, $exception)
    {
        $e = new Exception('The MAIA job could not be submitted.');
        Queue::push(new NoveltyDetectionFailure($event->job->id, $e));
    }
}
