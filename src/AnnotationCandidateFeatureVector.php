<?php

namespace Biigle\Modules\Maia;

use Biigle\Modules\Maia\Database\Factories\AnnotationCandidateFeatureVectorFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

#[Fillable(['id','job_id','vector',])]
#[WithoutTimestamps]
class AnnotationCandidateFeatureVector extends Model
{
    use HasFactory, HasNeighbors;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maia_annotation_candidate_feature_vectors';

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
        return AnnotationCandidateFeatureVectorFactory::new();
    }
}
