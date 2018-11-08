<?php

namespace Biigle\Modules\Maia\Events;

use Biigle\Modules\Maia\MaiaJob;

class MaiaJobDeleted
{
    /**
     * The ID of the job that caused this event.
     *
     * @var int
     */
    public $id;

    /**
     * Create a new instance
     *
     * @param MaiaJob $job
     */
    public function __construct(MaiaJob $job)
    {
        $this->id = $job->id;
    }
}
