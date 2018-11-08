<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Queue;
use TestCase;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionResponse;

class NoveltyDetectionRequestTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create();
        $request = new NoveltyDetectionRequest($job);
        Queue::fake();
        $request->handle();
        Queue::assertPushed(NoveltyDetectionResponse::class);

        $this->markTestIncomplete();
    }

    public function testHandleFailure()
    {
        $this->markTestIncomplete();
    }
}
