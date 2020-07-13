<?php

namespace Biigle\Modules\Maia;

use Biigle\Annotation;
use Biigle\Label;

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
        return $this->belongsTo(Annotation::class);
    }
}
