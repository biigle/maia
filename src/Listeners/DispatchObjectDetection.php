<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidateFeatureVectors;
use Biigle\Modules\Maia\Jobs\GenerateAnnotationCandidatePatches;
use Biigle\Modules\Maia\Jobs\NotifyObjectDetectionComplete;
use Biigle\Modules\Maia\Jobs\ObjectDetection;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
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
        $job = $event->job;
        // The job chain is used so the feature vectors are immediately generated
        // after the detection on the GPU queue. Otherwise another detection job
        // could squeeze inbetween and delay the generation of feature vectors by
        // hours.
        Bus::chain([
            new ObjectDetection($job),
            new GenerateAnnotationCandidatePatches($job),
            new GenerateAnnotationCandidateFeatureVectors($job),
            new NotifyObjectDetectionComplete($job),
        ])
        ->onConnection(config('maia.job_connection'))
        ->onQueue(config('maia.job_queue'))
        ->dispatch();
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
