<?php

namespace Biigle\Modules\Maia\Jobs;

use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionFailed;

class ObjectDetectionFailure extends JobFailure
{
    /**
     * {@inheritdoc}
     */
    protected function updateJobState(MaiaJob $job)
    {
        $job->state_id = State::failedObjectDetectionId();
    }

    /**
     * {@inheritdoc}
     */
    protected function sendNotification(MaiaJob $job)
    {
        $job->user->notify(new ObjectDetectionFailed($job));
    }
}
