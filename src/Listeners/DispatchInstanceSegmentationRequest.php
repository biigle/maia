<?php

namespace Biigle\Modules\Maia\Listeners;

use Queue;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;

class DispatchInstanceSegmentationRequest implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobContinued  $event
      * @return void
      */
    public function handle(MaiaJobContinued $event)
    {
        $request = new InstanceSegmentationRequest($event->job);
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
        $e = new Exception('The instance segmentation request could not be submitted.');
        Queue::push(new InstanceSegmentationFailure($event->job->id, $e));
    }
}
