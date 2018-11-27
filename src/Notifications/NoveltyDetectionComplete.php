<?php

namespace Biigle\Modules\Maia\Notifications;

class NoveltyDetectionComplete extends JobStateChanged
{
    /**
     * Get the title for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getTitle($job)
    {
        return "Training proposals ready for MAIA job {$job->id}";
    }

    /**
     * Get the message for the state change.
     *
     * @param MaiaJob $job
     * @return string
     */
    protected function getMessage($job)
    {
        return "The training proposals of MAIA job {$job->id} are ready for review.";
    }
}
