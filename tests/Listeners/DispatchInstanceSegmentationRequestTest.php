<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\Listeners\DispatchInstanceSegmentationRequest;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Queue;
use TestCase;

class DispatchInstanceSegmentationRequestTest extends TestCase
{
    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $event = new MaiaJobContinued($job);
        $listener = new DispatchInstanceSegmentationRequest;

        Queue::fake();
        $listener->failed($event, new Exception);
        Queue::assertPushed(InstanceSegmentationFailure::class);
    }
}
