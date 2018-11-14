<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Exception;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationResponse;

class InstanceSegmentationRequestTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create();
        $request = new InstanceSegmentationRequest($job);
        Queue::fake();
        $request->handle();
        Queue::assertPushed(InstanceSegmentationResponse::class);

        $this->markTestIncomplete();
    }

    public function testFailed()
    {
        $job = MaiaJobTest::create();
        $request = new InstanceSegmentationRequest($job);
        $e = new Exception;

        Queue::fake();
        $request->failed($e);
        Queue::assertPushed(InstanceSegmentationFailure::class);
    }
}
