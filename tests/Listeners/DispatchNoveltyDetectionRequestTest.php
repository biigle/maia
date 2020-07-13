<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\UseExistingAnnotations;
use Biigle\Modules\Maia\Listeners\DispatchNoveltyDetectionRequest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Bus;
use Queue;
use TestCase;

class DispatchNoveltyDetectionRequestTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobCreated($job);
        $listener = new DispatchNoveltyDetectionRequest;

        Queue::fake();
        $listener->handle($event);
        Queue::assertPushed(NoveltyDetectionRequest::class);
    }

    public function testHandleUseExisting()
    {
        $job = MaiaJobTest::create(['params' => ['use_existing' => true]]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchNoveltyDetectionRequest;

        Queue::fake();
        Bus::fake();
        $listener->handle($event);
        Queue::assertPushed(NoveltyDetectionRequest::class);
        Bus::assertDispatched(UseExistingAnnotations::class);
    }

    public function testHandleSkipNd()
    {
        $job = MaiaJobTest::create(['params' => [
            'use_existing' => true,
            'skip_nd' => true,
        ]]);
        $event = new MaiaJobCreated($job);
        $listener = new DispatchNoveltyDetectionRequest;

        Queue::fake();
        Bus::fake();
        $listener->handle($event);
        Queue::assertNotPushed(NoveltyDetectionRequest::class);
        Bus::assertDispatched(UseExistingAnnotations::class);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobCreated($job);
        $listener = new DispatchNoveltyDetectionRequest;

        Queue::fake();
        $listener->failed($event, new Exception);
        Queue::assertPushed(NoveltyDetectionFailure::class);
    }
}
