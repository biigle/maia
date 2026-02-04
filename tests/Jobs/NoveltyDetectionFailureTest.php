<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\NoveltyDetectionFailure;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionFailed;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Notification;
use Log;
use TestCase;

class NoveltyDetectionFailureTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new NoveltyDetectionFailure($job->id, $exception);

        Log::shouldReceive('error')->with("MAIA job {$job->id} failed!");
        Notification::fake();
        $failure->handle();
        Notification::assertSentTo($job->user, NoveltyDetectionFailed::class);

        $job->refresh();
        $this->assertSame(State::failedNoveltyDetectionId(), $job->state_id);
        $this->assertSame('This is the message.', $job->error['message']);
    }

    public function testHandleDetleted()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new NoveltyDetectionFailure($job->id, $exception);

        Log::shouldReceive('warning')->once();
        Notification::fake();
        $job->delete();
        $failure->handle();
        Notification::assertNothingSent();
    }
}
