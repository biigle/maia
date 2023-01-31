<?php

namespace Biigle\Modules\Maia\Jobs;

use Arr;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionFailed;
use Queue;

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
            $this->job->state_id = State::failedObjectDetectionId();
            $this->job->save();
            $this->job->user->notify(new ObjectDetectionFailed($this->job));

            return;
        }

        if ($this->job->shouldShowTrainingProposals()) {
            $this->selectTrainingProposals = false;
        }

        $this->convertAnnotations();

        if ($this->job->shouldShowTrainingProposals()) {
            // Pretend this to be a novelty detection response that returned all existing
            // annotations as training proposals.
            Queue::connection(config('maia.response_connection'))
                ->pushOn(config('maia.response_queue'), new NoveltyDetectionResponse($this->job->id, []));
        } else {
            // Continue with object detection using all existing annotations as
            // training data.
            event(new MaiaJobContinued($this->job));
        }
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
