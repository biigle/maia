<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Jobs\ObjectDetectionRequest;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;

class DispatchObjectDetectionRequest implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobContinued  $event
      * @return void
      */
    public function handle(MaiaJobContinued $event)
    {
        $request = new ObjectDetectionRequest($event->job);
        Queue::connection(config('maia.request_connection'))
            ->pushOn(config('maia.request_queue'), $request);
    }

    /**
     * Handle a job failure.
     *
     * @param  MaiaJobContinued  $event
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(MaiaJobContinued $event, $exception)
    {
        $e = new Exception('The object detection request could not be submitted.');
        Queue::push(new ObjectDetectionFailure($event->job->id, $e));
    }
}
