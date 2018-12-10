<?php

namespace Biigle\Modules\Maia\Policies;

use DB;
use Cache;
use Biigle\Role;
use Biigle\User;
use Biigle\Policies\CachedPolicy;
use Biigle\Modules\Maia\AnnotationCandidate;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnotationCandidatePolicy extends CachedPolicy
{
    use HandlesAuthorization;

    /**
     * Intercept all checks.
     *
     * @param User $user
     * @param string $ability
     * @return bool|null
     */
    public function before($user, $ability)
    {
        if ($user->can('sudo')) {
            return true;
        }
    }

    /**
     * Determine if the given annotation candidate can be accessed by the user.
     *
     * @param  User  $user
     * @param  AnnotationCandidate  $candidate
     * @return bool
     */
    public function access(User $user, AnnotationCandidate $candidate)
    {
        // Put this to persistent cache for rapid querying of candidate patches.
        return Cache::remember("maia-candidate-can-access-{$user->id}-{$candidate->job_id}", 0.5, function () use ($user, $candidate) {
            // Check if user is editor, expert or admin of one of the projects, the candidate belongs to.
            return DB::table('project_user')
                ->where('user_id', $user->id)
                ->whereIn('project_id', function ($query) use ($candidate) {
                    $query->select('project_id')
                        ->from('project_volume')
                        ->join('maia_jobs', 'project_volume.volume_id', '=', 'maia_jobs.volume_id')
                        ->where('maia_jobs.id', $candidate->job_id);
                })
                ->whereIn('project_role_id', [
                    Role::editorId(),
                    Role::expertId(),
                    Role::adminId(),
                ])
                ->exists();
        });
    }

    /**
     * Determine if the given user can update the annotation candidate.
     *
     * @param User $user
     * @param AnnotationCandidate $candidate
     *
     * @return bool
     */
    public function update(User $user, AnnotationCandidate $candidate)
    {
        return $this->access($user, $candidate);
    }
}
