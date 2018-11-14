<?php

namespace Biigle\Modules\Maia;

use Biigle\User;
use Biigle\Volume;
use biigle\Traits\HasJsonAttributes;
use Illuminate\Database\Eloquent\Model;
use Biigle\Modules\Maia\Events\MaiaJobCreated;
use Biigle\Modules\Maia\Events\MaiaJobDeleted;

class MaiaJob extends Model
{
    use HasJsonAttributes;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'attrs' => 'array',
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
     * Determine if the job failed during novelty detection or instance segmentation.
     *
     * @return boolean
     */
    public function hasFailed()
    {
        return $this->state_id === MaiaJobState::failedNoveltyDetectionId()
            || $this->state_id === MaiaJobState::failedInstanceSegmentationId();
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

    /**
     * Get the configured parameters of this job.
     *
     * @return array
     */
    public function getParamsAttribute()
    {
        return $this->getJsonAttr('params');
    }

    /**
     * Set the configured parameters of this job.
     *
     * @param array $params
     */
    public function setParamsAttribute(array $params)
    {
        return $this->setJsonAttr('params', $params);
    }

    /**
     * Get the error information on this job (if any).
     *
     * @return array
     */
    public function getErrorAttribute()
    {
        return $this->getJsonAttr('error');
    }

    /**
     * Set the error information for this job.
     *
     * @param array $error
     */
    public function setErrorAttribute(array $error)
    {
        return $this->setJsonAttr('error', $error);
    }
}
