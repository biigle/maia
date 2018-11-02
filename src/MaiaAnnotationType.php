<?php

namespace Biigle\Modules\Maia;

use Cache;
use Illuminate\Database\Eloquent\Model;

class MaiaAnnotationType extends Model
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
    public static $trainingProposalId;

    /**
     * The manual filtering and refinement of training proposals stage ID.
     *
     * @var int
     */
    public static $annotationCandidateId;
}

MaiaAnnotationType::$trainingProposalId = Cache::rememberForever('maia-annotation-type-training-proposal', function () {
    return MaiaAnnotationType::whereName('training-proposal')->first()->id;
});

MaiaAnnotationType::$annotationCandidateId = Cache::rememberForever('maia-annotation-type-annotation-candidate', function () {
    return MaiaAnnotationType::whereName('annotation-candidate')->first()->id;
});
