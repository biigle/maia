<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\Listeners\DispatchObjectDetectionRequest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Queue;
use TestCase;

class DispatchObjectDetectionRequestTest extends TestCase
{
    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobContinued($job);
        $listener = new DispatchObjectDetectionRequest;

        Queue::fake();
        $listener->failed($event, new Exception);
        Queue::assertPushed(ObjectDetectionFailure::class);
    }
}
