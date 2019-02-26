<?php

namespace Biigle\Modules\Maia\Events;

use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Queue\SerializesModels;

class MaiaJobDeleting
{
    use SerializesModels;

    /**
     * The job that caused this event.
     *
     * @var MaiaJob
     */
    public $job;

    /**
     * Create a new instance
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        $this->job = $job;
    }
}
