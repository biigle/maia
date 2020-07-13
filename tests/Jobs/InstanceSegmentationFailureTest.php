<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\InstanceSegmentationFailure;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationFailed;
use Biigle\Tests\Modules\Maia\MaiaJobTest;
use Exception;
use Illuminate\Support\Facades\Notification;
use Log;
use TestCase;

class InstanceSegmentationFailureTest extends TestCase
{
    public function testHandle()
    {
        $job = MaiaJobTest::create(['state_id' => State::noveltyDetectionId()]);
        $exception = new Exception('This is the message.');
        $failure = new InstanceSegmentationFailure($job->id, $exception);

        Log::shouldReceive('error')->with("MAIA job {$job->id} failed!");
        Notification::fake();
        $failure->handle();
        Notification::assertSentTo($job->user, InstanceSegmentationFailed::class);

        $job->refresh();
        $this->assertEquals(State::failedInstanceSegmentationId(), $job->state_id);
        $this->assertEquals('This is the message.', $job->error['message']);
    }
}
