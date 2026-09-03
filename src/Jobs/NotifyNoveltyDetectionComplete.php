<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;
use Illuminate\Queue\SerializesModels;

#[DeleteWhenMissingModels]
class NotifyNoveltyDetectionComplete extends Job implements ShouldQueue
{
    use SerializesModels;

    /**
     * Create a new isntance.
     */
    public function __construct(public MaiaJob $job)
    {
        //
    }

    /**
      * Handle the job.
      *
      * @return void
      */
    public function handle()
    {
        $this->job->user->notify(new NoveltyDetectionComplete($this->job));
    }
}
