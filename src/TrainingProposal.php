<?php

namespace Biigle\Modules\Maia;

use Biigle\Modules\Maia\Database\Factories\TrainingProposalFactory;

class TrainingProposal extends MaiaAnnotation
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maia_training_proposals';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'points' => 'array',
        'score' => 'float',
        'selected' => 'boolean',
    ];

    /**
     * Scope the query to all selected annotations
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelected($query)
    {
        return $query->where('maia_training_proposals.selected', true);
    }

    /**
     * Scope the query to all unselected annotations
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnselected($query)
    {
        return $query->where('maia_training_proposals.selected', false);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TrainingProposalFactory::new();
    }
}
