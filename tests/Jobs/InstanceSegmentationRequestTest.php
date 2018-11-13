<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Jobs\InstanceSegmentationRequest;
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

    public function testHandleFailure()
    {
        $this->markTestIncomplete();
    }
}
