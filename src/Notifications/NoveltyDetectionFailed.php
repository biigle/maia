<?php

namespace Biigle\Modules\Maia\Notifications;

class NoveltyDetectionFailed extends JobStateChanged
{
    /**
     * Get the title for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return "MAIA job {$job->id} failed";
    }

    /**
     * Get the message for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return "MAIA job {$job->id} failed during novelty detection. Please notify the BIIGLE administrators.";
    }
}
