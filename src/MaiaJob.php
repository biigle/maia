<?php

namespace Biigle\Modules\Maia;

use Biigle\User;
use Biigle\Volume;
use Illuminate\Database\Eloquent\Model;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleted;

class MaiaJob extends Model
{
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'params' => 'array',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => MaiaJobCreated::class,
        'deleted' => MaiaJobDeleted::class,
    ];

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

    /**
     * The training proposals of this MAIA job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingProposals()
    {
        return $this->annotations()->trainingProposals();
    }

    /**
     * The annotation candidates of this MAIA job.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function annotationCandidates()
    {
        return $this->annotations()->annotationCandidates();
    }

    /**
     * Determine if the job is currently running novelty detection or instance segmentation.
     *
     * @return boolean
     */
    public function isRunning()
    {
        return $this->state_id === MaiaJobState::noveltyDetectionId()
            || $this->state_id === MaiaJobState::instanceSegmentationId();
    }

    /**
     * Determine if the job requires a user action to continue
     *
     * @return boolean
     */
    public function requiresAction()
    {
        return $this->state_id === MaiaJobState::trainingProposalsId();
    }
}
