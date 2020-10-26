<?php

namespace Biigle\Modules\Maia\Jobs;

use Arr;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationFailed;

class PrepareExistingAnnotations extends PrepareAnnotationsJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$this->hasAnnotations()) {
            $this->job->error = ['message' => 'Existing annotations should be used but there are no existing annotations to take as training proposals.'];
            $this->job->state_id = State::failedInstanceSegmentationId();
            $this->job->save();
            $this->job->user->notify(new InstanceSegmentationFailed($this->job));

            return;
        }

        $this->convertAnnotations();
        event(new MaiaJobContinued($this->job));
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationsQuery()
    {
        $restrictLabels = Arr::get($this->job->params, 'oa_restrict_labels', []);

        return $this->getExistingAnnotationsQuery($this->job->volume_id, $restrictLabels);
    }
}
