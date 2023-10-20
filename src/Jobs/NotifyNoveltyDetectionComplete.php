<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Jobs\Job;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Queue\SerializesModels;
use Biigle\Modules\Maia\Notifications\NoveltyDetectionComplete;

class NotifyNoveltyDetectionComplete extends Job
{
    use SerializesModels;

    /**
     * Ignore this job if the MAIA job does not exist any more.
     *
     * @var bool
     */
    protected $deleteWhenMissingModels = true;

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
