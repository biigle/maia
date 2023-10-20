<?php

namespace Biigle\Tests\Modules\Maia\Jobs;

use Biigle\Modules\Maia\Jobs\NotifyNoveltyDetectionComplete;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Support\Facades\Notification;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use TestCase;

class NotifyNoveltyDetectionCompleteTest extends TestCase
{
    public function testHandle()
    {
        Notification::fake();
        $job = MaiaJob::factory()->create();
        $j = new NotifyNoveltyDetectionComplete($job);
        $j->handle();
        Notification::assertSentTo($job->user, NoveltyDetectionComplete::class);
    }
}
