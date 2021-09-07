<?php

namespace Biigle\Modules\Maia\Jobs;

use Arr;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\InstanceSegmentationFailed;
use Biigle\Volume;
use DB;

class PrepareKnowledgeTransfer extends PrepareAnnotationsJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $otherVolume = Volume::find($this->job->params['kt_volume_id']);

        if (is_null($otherVolume)) {
            $this->handleFailure('The volume that was selected for knowledge transfer does not exist.');
            return;
        }

        $missingOtherMetadata = $otherVolume->images()
            ->whereNull('attrs->metadata->distance_to_ground')
            ->exists();

        if ($missingOtherMetadata) {
            $this->handleFailure('The volume that was selected for knowledge transfer has images where the distance to ground information is missing.');
            return;
        }

        $missingOwnMetadata = $this->job->volume->images()
            ->whereNull('attrs->metadata->distance_to_ground')
            ->exists();

        if ($missingOwnMetadata) {
            $this->handleFailure('The volume of the MAIA job has images where the distance to ground information is missing.');
            return;
        }

        $column = DB::raw("cast(attrs->'metadata'->>'distance_to_ground' as real)");
        $ownDistance = floatval($this->job->volume->images()->avg($column));

        if ($ownDistance <= 0.0) {
            $this->handleFailure('The average distance to ground of the volume of the MAIA job is non-positive.');
            return;
        }

        $column = DB::raw("attrs->'metadata'->>'distance_to_ground' as distance");
        $scaleFactors = $otherVolume->images()
            ->select($column, 'id')
            ->pluck('distance', 'id')
            ->map(function ($item, $key) use ($ownDistance) {
                return floatval($item) / $ownDistance;
            });

        if ($scaleFactors->min() <= 0) {
            $this->handleFailure('The distances to ground of the volume that was selected for knowledge transfer include non-positive numbers.');
            return;
        }

        $params = $this->job->params;
        $params['kt_scale_factors'] = $scaleFactors;
        $this->job->params = $params;


        if (!$this->hasAnnotations()) {
            $this->handleFailure('The volume that was selected for knowledge transfer has no annotations.');
            return;
        }

        $this->convertAnnotations();
        $this->job->save();
        event(new MaiaJobContinued($this->job));
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationsQuery()
    {
        $restrictLabels = Arr::get($this->job->params, 'kt_restrict_labels', []);

        return $this->getExistingAnnotationsQuery($this->job->params['kt_volume_id'], $restrictLabels);
    }

    /**
     * Set the failed state and error message and notify the user.
     *
     * @param string $message
     */
    protected function handleFailure($message)
    {
        $this->job->error = ['message' => $message];
        $this->job->state_id = State::failedInstanceSegmentationId();
        $this->job->save();
        $this->job->user->notify(new InstanceSegmentationFailed($this->job));
    }
}
