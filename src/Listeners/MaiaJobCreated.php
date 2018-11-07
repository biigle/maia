<?php

namespace Biigle\Modules\Maia\Listeners;

use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Biigle\Modules\Maia\Jobs\NoveltyDetectionRequest;

class MaiaJobCreated implements ShouldQueue
{
    /**
     * Queue to use for new jobs.
     *
     * @var string
     */
    protected $queue;

    /**
     * Queue connection to use for new jobs.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->queue = config('maia.request_queue');
        $this->connection = config('maia.request_connection');
    }

   /**
     * Handle the event.
     *
     * @param  MaiaJob  $job
     * @return void
     */
    public function handle(MaiaJob $job)
    {
        NoveltyDetectionRequest::dispatch($job)
            ->onQueue($this->queue)
            ->onConnection($this->connection);
    }
}
