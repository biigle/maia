<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\PrepareExistingAnnotations;
use Biigle\Modules\Maia\Jobs\PrepareKnowledgeTransfer;
use Biigle\Modules\Maia\Listeners\DispatchMaiaJob;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Event;
use Exception;
use Illuminate\Support\Facades\Bus;
use Queue;
use TestCase;

class DispatchMaiaJobTest extends TestCase
{
    public function testHandleNoveltyDetection()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'novelty_detection']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        Queue::fake();
        $listener->handle($event);
        Queue::assertPushed(NoveltyDetectionRequest::class);
    }

    public function testHandleExistingAnnotations()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'own_annotations']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        Bus::fake();
        $listener->handle($event);
        Bus::assertDispatched(PrepareExistingAnnotations::class);
    }

    public function testHandleKnowledgeTransfer()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'knowledge_transfer']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        Bus::fake();
        $listener->handle($event);
        Bus::assertDispatched(PrepareKnowledgeTransfer::class);
    }

    public function testHandleAreaKnowledgeTransfer()
    {
        $job = MaiaJobTest::create(['params' => ['training_data_method' => 'area_knowledge_transfer']]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        Bus::fake();
        $listener->handle($event);
        Bus::assertDispatched(PrepareKnowledgeTransfer::class);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobCreated($job);
        $listener = new DispatchMaiaJob;

        Queue::fake();
        $listener->failed($event, new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
    }
}
