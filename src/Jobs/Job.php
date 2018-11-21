<?php

namespace Biigle\Modules\Maia\Jobs;

use Illuminate\Bus\Queueable;

/**
 * This class is used for jobs as \Biigle\Jobs\Job may not available in biigle/gpus.
 */
abstract class Job
{
    use Queueable;
}
