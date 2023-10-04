<?php

namespace Biigle\Modules\Maia;

use Biigle\Modules\Maia\Database\Factories\TrainingProposalEmbeddingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Pgvector\Laravel\HasNeighbors;
use Pgvector\Laravel\Vector;

class TrainingProposalEmbedding extends Model
{
    use HasFactory, HasNeighbors;

    /**
     * The database connection associated with the model.
     *
     * @var string
     */
    protected $connection = 'pgvector';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maia_training_proposal_embeddings';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'embedding' => Vector::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'embedding',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TrainingProposalEmbeddingFactory::new();
    }
}
