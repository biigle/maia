<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\ProcessNewAnnotationCandidates;
use Biigle\Modules\Maia\Jobs\NotifyObjectDetectionComplete;
use Biigle\Modules\Maia\Jobs\ObjectDetection;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Listeners\DispatchObjectDetection;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use TestCase;

class DispatchObjectDetectionTest extends TestCase
{
    public function testHandle()
    {
        Bus::fake();
        $job = MaiaJobTest::create();
        $event = new MaiaJobContinued($job);
        $listener = new DispatchObjectDetection;

        $listener->handle($event);

        Bus::assertChained([
            ObjectDetection::class,
            ProcessNewAnnotationCandidates::class,
            NotifyObjectDetectionComplete::class,
        ]);
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobContinued($job);
        $listener = new DispatchObjectDetection;

        Queue::fake();
        $listener->failed($event, new Exception);
        Queue::assertPushed(ObjectDetectionFailure::class);
    }
}
