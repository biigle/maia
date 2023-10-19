<?php

namespace Biigle\Modules\Maia\Jobs;

use Arr;
use Biigle\Modules\Maia\Events\MaiaJobContinued;
use Biigle\Modules\Maia\Exceptions\PrepareKnowledgeTransferException;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Modules\Maia\MaiaJobState as State;
use Biigle\Modules\Maia\Notifications\ObjectDetectionFailed;
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
        try {
            if ($this->job->params['training_data_method'] === MaiaJob::TRAIN_AREA_KNOWLEDGE_TRANSFER) {
                $scaleFactors = $this->getAreaScaleFactors();
            } else {
                $scaleFactors = $this->getDistanceScaleFactors();
            }

            if (!$this->hasAnnotations()) {
                throw new PrepareKnowledgeTransferException('The volume that was selected for knowledge transfer has no annotations.');
            }
        } catch (PrepareKnowledgeTransferException $e) {
            $this->job->error = ['message' => $e->getMessage()];
            $this->job->state_id = State::failedObjectDetectionId();
            $this->job->save();
            $this->job->user->notify(new ObjectDetectionFailed($this->job));
            return;
        }

        $params = $this->job->params;
        $params['kt_scale_factors'] = $scaleFactors;
        $this->job->params = $params;


        // There is no option to show the "training proposals" here, so we skip all that.
        $this->convertAnnotations();
        $this->job->save();
        event(new MaiaJobContinued($this->job));
    }

    /**
     * Get the scale factors based on distance to ground.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getDistanceScaleFactors()
    {
        $otherVolume = Volume::find($this->job->params['kt_volume_id']);

        if (is_null($otherVolume)) {
            throw new PrepareKnowledgeTransferException('The volume that was selected for knowledge transfer does not exist.');
        }

        $missingOtherMetadata = $otherVolume->images()
            ->whereNull('attrs->metadata->distance_to_ground')
            ->exists();

        if ($missingOtherMetadata) {
            throw new PrepareKnowledgeTransferException('The volume that was selected for knowledge transfer has images where the distance to ground information is missing.');
        }

        $missingOwnMetadata = $this->job->volume->images()
            ->whereNull('attrs->metadata->distance_to_ground')
            ->exists();

        if ($missingOwnMetadata) {
            throw new PrepareKnowledgeTransferException('The volume of the MAIA job has images where the distance to ground information is missing.');
        }

        $column = DB::raw("cast(attrs->'metadata'->>'distance_to_ground' as real)");
        $ownDistance = floatval($this->job->volume->images()->avg($column));

        if ($ownDistance <= 0.0) {
            throw new PrepareKnowledgeTransferException('The average distance to ground of the volume of the MAIA job is non-positive.');
        }

        $column = DB::raw("attrs->'metadata'->>'distance_to_ground' as distance");
        $scaleFactors = $otherVolume->images()
            ->select($column, 'id')
            ->pluck('distance', 'id')
            ->map(function ($item, $key) use ($ownDistance) {
                return floatval($item) / $ownDistance;
            });

        if ($scaleFactors->min() <= 0) {
            throw new PrepareKnowledgeTransferException('The distances to ground of the volume that was selected for knowledge transfer include non-positive numbers.');
        }

        return $scaleFactors;
    }

    /**
     * Get the scale factors based on image area.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getAreaScaleFactors()
    {
        $otherVolume = Volume::find($this->job->params['kt_volume_id']);

        if (is_null($otherVolume)) {
            throw new PrepareKnowledgeTransferException('The volume that was selected for knowledge transfer does not exist.');
        }

        $column = DB::raw("COALESCE(attrs->'metadata'->>'area', attrs->'laserpoints'->>'area')");

        $missingOtherMetadata = $otherVolume->images()
            ->whereNull($column)
            ->exists();

        if ($missingOtherMetadata) {
            throw new PrepareKnowledgeTransferException('The volume that was selected for knowledge transfer has images where the area information is missing.');
        }

        $missingOwnMetadata = $this->job->volume->images()
            ->whereNull($column)
            ->exists();

        if ($missingOwnMetadata) {
            throw new PrepareKnowledgeTransferException('The volume of the MAIA job has images where the area information is missing.');
        }

        $column = DB::raw("cast(COALESCE(attrs->'metadata'->>'area', attrs->'laserpoints'->>'area') as real)");
        $ownArea = floatval($this->job->volume->images()->avg($column));

        if ($ownArea <= 0.0) {
            throw new PrepareKnowledgeTransferException('The average image area of the volume of the MAIA job is non-positive.');
        }

        $column = DB::raw("COALESCE(attrs->'metadata'->>'area', attrs->'laserpoints'->>'area') as area");
        $scaleFactors = $otherVolume->images()
            ->select($column, 'id')
            ->pluck('area', 'id')
            ->map(function ($item, $key) use ($ownArea) {
                return floatval($item) / $ownArea;
            });

        if ($scaleFactors->min() <= 0) {
            throw new PrepareKnowledgeTransferException('The image areas of the volume that was selected for knowledge transfer include non-positive numbers.');
        }

        return $scaleFactors;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAnnotationsQuery()
    {
        $restrictLabels = Arr::get($this->job->params, 'kt_restrict_labels', []);

        return $this->getExistingAnnotationsQuery($this->job->params['kt_volume_id'], $restrictLabels);
    }

}
