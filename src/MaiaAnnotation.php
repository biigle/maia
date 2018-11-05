<?php

namespace Biigle\Modules\Maia;

use Biigle\Image;
use Biigle\Shape;
use Biigle\Traits\HasPointsAttribute;
use Illuminate\Database\Eloquent\Model;
use Biigle\Contracts\Annotation as AnnotationContract;

class MaiaAnnotation extends Model implements AnnotationContract
{
    use HasPointsAttribute;

    /**
     * Don't maintain timestamps for this model.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'points' => 'array',
        'selected' => 'boolean',
    ];

    /**
     * Scope the query to all training proposals.
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTrainingProposals($query)
    {
        return $query->where('maia_annotations.type_id', MaiaAnnotationType::trainingProposalId());
    }

    /**
     * Scope the query to all annotation candidates.
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAnnotationCandidates($query)
    {
        return $query->where('maia_annotations.type_id', MaiaAnnotationType::annotationCandidateId());
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
        return $query->where('maia_annotations.selected', false);
    }

    /**
     * The image, this MAIA annotation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * The shape of this MAIA annotation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shape()
    {
        return $this->belongsTo(Shape::class);
    }

    /**
     * The type of this MAIA annotation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(MaiaAnnotationType::class);
    }

    /**
     * The MAIA job, this MAIA anotation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo(MaiaJob::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    /**
     * {@inheritdoc}
     */
    public function getShape(): Shape
    {
        return $this->shape;
    }

    /**
     * {@inheritdoc}
     */
    public function getImage(): Image
    {
        return $this->image;
    }
}
