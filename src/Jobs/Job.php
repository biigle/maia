<?php

namespace Biigle\Modules\Maia\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * This class is used for jobs as \Biigle\Jobs\Job may not available in biigle/gpu-server.
 */
abstract class Job
{
    use Queueable, Dispatchable;
}
