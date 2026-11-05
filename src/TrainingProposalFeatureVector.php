<?php

namespace Biigle\Modules\Maia;

use Biigle\Modules\Maia\Database\Factories\TrainingProposalFeatureVectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\Vector;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Attributes\Fillable;
#[Fillable(['id','job_id','vector',])]
#[WithoutTimestamps]
class TrainingProposalFeatureVector extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maia_training_proposal_feature_vectors';


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'vector' => Vector::class,
    ];

   

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TrainingProposalFeatureVectorFactory::new();
    }
}
