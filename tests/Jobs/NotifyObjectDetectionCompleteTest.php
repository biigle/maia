<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\NotifyObjectDetectionComplete;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Support\Facades\Notification;
use Biigle\Modules\Maia\Notifications\ObjectDetectionComplete;
use TestCase;

class NotifyObjectDetectionCompleteTest extends TestCase
{
    public function testHandle()
    {
        Notification::fake();
        $job = MaiaJob::factory()->create();
        $j = new NotifyObjectDetectionComplete($job);
        $j->handle();
        Notification::assertSentTo($job->user, ObjectDetectionComplete::class);
    }
}
