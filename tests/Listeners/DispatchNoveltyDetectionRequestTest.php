<?php

namespace Biigle\Tests\Modules\Maia\Listeners;

use Queue;
use TestCase;
use Exception;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Listeners\DispatchNoveltyDetectionRequest;

class DispatchNoveltyDetectionRequestTest extends TestCase
{
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
