<?php

namespace Biigle\Modules\Maia;

use Cache;
use Illuminate\Database\Eloquent\Model;

class MaiaJobState extends Model
{
    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The novelty detection stage ID.
     *
     * @var int
     */
    public static $noveltyDetectionId;

    /**
     * The manual filtering and refinement of training proposals stage ID.
     *
     * @var int
     */
    public static $trainingProposalsId;

    /**
     * The instance segmentation stage ID.
     *
     * @var int
     */
    public static $instanceSegmentationId;

    /**
     * The manual filtering of annotation candidated stage ID.
     *
     * @var int
     */
    public static $annotationCandidatesId;
}

MaiaJobState::$noveltyDetectionId = Cache::rememberForever('maia-job-state-novelty-detection', function () {
    return MaiaJobState::whereName('novelty-detection')->first()->id;
});

MaiaJobState::$trainingProposalsId = Cache::rememberForever('maia-job-state-training-proposals', function () {
    return MaiaJobState::whereName('training-proposals')->first()->id;
});

MaiaJobState::$instanceSegmentationId = Cache::rememberForever('maia-job-state-instance-segmentation', function () {
    return MaiaJobState::whereName('instance-segmentation')->first()->id;
});

MaiaJobState::$annotationCandidatesId = Cache::rememberForever('maia-job-state-annotation-candidates', function () {
    return MaiaJobState::whereName('annotation-candidates')->first()->id;
});
