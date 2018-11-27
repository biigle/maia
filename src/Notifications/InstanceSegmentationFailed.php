<?php

namespace Biigle\Modules\Maia\Notifications;

class InstanceSegmentationFailed extends JobStateChanged
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
        return "MAIA job {$job->id} failed during instance segmentation. Please notify the BIIGLE administrators.";
    }
}
