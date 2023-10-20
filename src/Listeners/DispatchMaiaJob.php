<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalPatches;
use Biigle\Modules\Maia\Jobs\NotifyNoveltyDetectionComplete;
use Biigle\Modules\Maia\Jobs\NoveltyDetection;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\PrepareExistingAnnotations;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;

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
        $job = $event->job;
        if ($job->shouldUseNoveltyDetection()) {
            // The job chain is used so the feature vectors are immediately generated
            // after the detection on the GPU queue. Otherwise another detection job
            // could squeeze inbetween and delay the generation of feature vectors by
            // hours.
            Bus::chain([
                new NoveltyDetection($job),
                new GenerateTrainingProposalPatches($job),
                new GenerateTrainingProposalFeatureVectors($job),
                new NotifyNoveltyDetectionComplete($job),
            ])
            ->onConnection(config('maia.job_connection'))
        ->onQueue(config('maia.job_queue'))
            ->dispatch();
        } elseif ($job->shouldUseExistingAnnotations()) {
            if ($job->shouldShowTrainingProposals()) {
                // The job chain is used so the feature vectors are immediately generated
                // after the detection on the GPU queue. Otherwise another detection job
                // could squeeze inbetween and delay the generation of feature vectors by
                // hours.
                Bus::chain([
                    new PrepareExistingAnnotations($job),
                    new GenerateTrainingProposalPatches($job),
                    new GenerateTrainingProposalFeatureVectors($job),
                    // TODO: Send a different notification?
                    new NotifyNoveltyDetectionComplete($job),
                ])
                ->dispatch();
            } else {
                Queue::push(new PrepareExistingAnnotations($job));
            }
        } elseif ($job->shouldUseKnowledgeTransfer()) {
            Queue::push(new PrepareKnowledgeTransfer($job));
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
