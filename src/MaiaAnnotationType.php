<?php

namespace Biigle\Modules\Maia;

use Biigle\Traits\HasConstantInstances;
use Illuminate\Database\Eloquent\Model;

class MaiaAnnotationType extends Model
{
    use HasConstantInstances;

    /**
     * The constant instances of this model.
     *
     * @var array
     */
    const INSTANCES = [
        'trainingProposal' => 'training-proposal',
        'annotationCandidate' => 'annotation-candidate',
    ];

    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;
}
