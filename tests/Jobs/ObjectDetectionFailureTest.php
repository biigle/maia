<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\ObjectDetectionFailure;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionFailed;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Notification;
use Log;
use TestCase;

class ObjectDetectionFailureTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new ObjectDetectionFailure($job->id, $exception);

        Log::shouldReceive('error')->with("MAIA job {$job->id} failed!");
        Notification::fake();
        $failure->handle();
        Notification::assertSentTo($job->user, ObjectDetectionFailed::class);

        $job->refresh();
        $this->assertSame(State::failedObjectDetectionId(), $job->state_id);
        $this->assertSame('This is the message.', $job->error['message']);
    }
}
