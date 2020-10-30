<?php

namespace Biigle\Modules\Maia\Traits;

use Biigle\ImageAnnotation;

trait QueriesExistingAnnotations
{
    /**
     * Get the query for the annotations to convert.
     *
     * @param int $volumeId
     * @param array $restrictLabels
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getExistingAnnotationsQuery($volumeId, $restrictLabels = [])
    {
        return ImageAnnotation::join('images', 'image_annotations.image_id', '=', 'images.id')
            ->where('images.volume_id', $volumeId)
            ->when(!empty($restrictLabels), function ($query) use ($restrictLabels) {
                return $query->join('image_annotation_labels', 'image_annotation_labels.annotation_id', '=', 'image_annotations.id')
                    ->whereIn('image_annotation_labels.label_id', $restrictLabels);
            });
    }
}
