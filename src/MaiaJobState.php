<?php

namespace Biigle\Modules\Maia;

use Biigle\Traits\HasConstantInstances;
use Illuminate\Database\Eloquent\Model;

class MaiaJobState extends Model
{
    use HasConstantInstances;

    /**
     * The constant instances of this model.
     *
     * @var array
     */
    const INSTANCES = [
        // The novelty detection stage.
        'noveltyDetection' => 'novelty-detection',
        // The manual selection and refinement of training proposals stage.
        'trainingProposals' => 'training-proposals',
        // The instance segmentation stage.
        'instanceSegmentation' => 'instance-segmentation',
        // The manual review of annotation candidates stage.
        'annotationCandidates' => 'annotation-candidates',
    ];

    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;
}
