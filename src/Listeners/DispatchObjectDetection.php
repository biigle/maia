<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Jobs\ObjectDetection;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Queue;

class DispatchObjectDetection implements ShouldQueue
{
    /**
      * Handle the event.
      *
      * @param  MaiaJobContinued  $event
      * @return void
      */
    public function handle(MaiaJobContinued $event)
    {
        $job = new ObjectDetection($event->job);
        Queue::connection(config('maia.job_connection'))
            ->pushOn(config('maia.job_queue'), $job);
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
