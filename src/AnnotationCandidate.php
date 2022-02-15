<?php

namespace Biigle\Modules\Maia;

use Biigle\ImageAnnotation;
use Biigle\Label;
use Biigle\Modules\Maia\Database\Factories\AnnotationCandidateFactory;

class AnnotationCandidate extends MaiaAnnotation
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maia_annotation_candidates';

    /**
     * The label that has been (maybe) attached to this candidate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    /**
     * The annotation that was (maybe) created based on this candidate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function annotation()
    {
        return $this->belongsTo(ImageAnnotation::class);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return AnnotationCandidateFactory::new();
    }
}
