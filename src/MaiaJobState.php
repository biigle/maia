<?php

namespace Biigle\Modules\Maia;

use Biigle\Modules\Maia\Database\Factories\MaiaJobStateFactory;
use Biigle\Traits\HasConstantInstances;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaiaJobState extends Model
{
    use HasConstantInstances, HasFactory;

    /**
     * The constant instances of this model.
     *
     * @var array
     */
    const INSTANCES = [
        // The novelty detection stage.
        'noveltyDetection' => 'novelty-detection',
        // A failure during novelty detection.
        'failedNoveltyDetection' => 'failed-novelty-detection',
        // The manual selection and refinement of training proposals stage.
        'trainingProposals' => 'training-proposals',
        // The object detection stage.
        // NOTE: This was incorrectly called instance segmentation before and the
        // entry in the DB still uses this term.
        'objectDetection' => 'instance-segmentation',
        // A failure during object detection.
        // NOTE: This was incorrectly called instance segmentation before and the
        // entry in the DB still uses this term.
        'failedObjectDetection' => 'failed-instance-segmentation',
        // The manual review of annotation candidates stage.
        'annotationCandidates' => 'annotation-candidates',
    ];

    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return MaiaJobStateFactory::new();
    }
}
