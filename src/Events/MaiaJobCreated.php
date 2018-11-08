<?php

namespace Biigle\Modules\Maia\Events;

use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Queue\SerializesModels;

class MaiaJobCreated
{
    use SerializesModels;

    /**
     * The job that was created.
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
