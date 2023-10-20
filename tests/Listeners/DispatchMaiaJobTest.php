<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalFeatureVectors;
use Biigle\Modules\Maia\Jobs\GenerateTrainingProposalPatches;
use Biigle\Modules\Maia\Jobs\NotifyNoveltyDetectionComplete;
use Biigle\Modules\Maia\Jobs\NoveltyDetection;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\PrepareExistingAnnotations;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Biigle\Modules\Maia\Listeners\DispatchMaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use TestCase;

class DispatchMaiaJobTest extends TestCase
{
    public function testHandleNoveltyDetection()
    {
        Bus::fake();
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'novelty_detection']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->handle($event);
        Bus::assertChained([
            NoveltyDetection::class,
            GenerateTrainingProposalPatches::class,
            GenerateTrainingProposalFeatureVectors::class,
            NotifyNoveltyDetectionComplete::class,
        ]);
    }

    public function testHandleExistingAnnotations()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'own_annotations']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->handle($event);
        Queue::assertPushed(PrepareExistingAnnotations::class);
    }

    public function testHandleExistingAnnotationsShow()
    {
        Bus::fake();
        $job = MaiaJobTest::create(['params' => [
            'training_data_method' => 'own_annotations',
            'oa_show_training_proposals' => true,
        ]]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->handle($event);
        Bus::assertChained([
            PrepareExistingAnnotations::class,
            GenerateTrainingProposalPatches::class,
            GenerateTrainingProposalFeatureVectors::class,
            NotifyNoveltyDetectionComplete::class,
        ]);
    }

    public function testHandleKnowledgeTransfer()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'knowledge_transfer']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->handle($event);
        Queue::assertPushed(PrepareKnowledgeTransfer::class);
    }

    public function testHandleAreaKnowledgeTransfer()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'area_knowledge_transfer']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->handle($event);
        Queue::assertPushed(PrepareKnowledgeTransfer::class);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        $listener->failed($event, new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
    }
}
