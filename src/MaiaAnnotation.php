<?php

namespace Biigle\Modules\Maia;

use Biigle\Contracts\Annotation as AnnotationContract;
use Biigle\Image;
use Biigle\Shape;
use Biigle\Traits\HasPointsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class MaiaAnnotation extends Model implements AnnotationContract
{
    use HasPointsAttribute, HasFactory;

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
        'score' => 'float',
    ];

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
    public function getFile(): Image
    {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): int
    {
        return $this->id;
    }
}
