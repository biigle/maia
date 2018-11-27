<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use TestCase;
use Exception;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Illuminate\Support\Facades\Notification;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionFailed;

class NoveltyDetectionFailureTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new NoveltyDetectionFailure($job->id, $exception);

        Notification::fake();
        $failure->handle();
        Notification::assertSentTo($job->user, NoveltyDetectionFailed::class);

        $job->refresh();
        $this->assertEquals(State::failedNoveltyDetectionId(), $job->state_id);
        $this->assertEquals('This is the message.', $job->error['message']);
    }
}
