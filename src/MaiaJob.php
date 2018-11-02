<?php

namespace Biigle\Modules\Maia;

use Biigle\User;
use Biigle\Volume;
use Illuminate\Database\Eloquent\Model;

class MaiaJob extends Model
{
    /**
     * The volume, this MAIA job belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function volume()
    {
        return $this->belongsTo(Volume::class);
    }

    /**
     * The user who created this MAIA job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The state of this MAIA job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(MaiaJobState::class);
    }

    /**
     * The annotations belonging to this MAIA job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function annotations()
    {
        return $this->hasMany(MaiaAnnotation::class, 'job_id');
    }
}
