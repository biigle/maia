<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use TestCase;
use Exception;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;

class NoveltyDetectionFailureTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new NoveltyDetectionFailure($job->id, $exception);

        $failure->handle();

        $job->refresh();
        $this->assertEquals(State::failedNoveltyDetectionId(), $job->state_id);
        $this->assertEquals('This is the message.', $job->error['message']);
    }
}
