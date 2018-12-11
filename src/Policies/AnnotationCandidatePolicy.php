<?php

namespace Biigle\Modules\Maia\Policies;

use DB;
use Cache;
use Biigle\Role;
use Biigle\User;
use Biigle\Label;
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

    /**
     * Determine if the user can attach the given label to the given candidate.
     *
     * The candidate (image) must belong to a project where the user is an editor or
     * admin. The label must belong to a label tree that is used by one of the projects
     * the user and the candidate belong to.
     *
     * @param  User  $user
     * @param  AnnotationCandidate  $candidate
     * @param  Label  $label
     * @return bool
     */
    public function attachLabel(User $user, AnnotationCandidate $candidate, Label $label)
    {
        return $this->remember("annotation-candidate-can-attach-label-{$user->id}-{$candidate->job_id}-{$label->id}", function () use ($user, $candidate, $label) {
            // Projects, the candidate belongs to *and* the user is editor, expert or admin of.
            $projectIds = DB::table('project_user')
                ->where('user_id', $user->id)
                ->whereIn('project_id', function ($query) use ($candidate) {
                    // the projects, the candidate belongs to
                    $query->select('project_volume.project_id')
                        ->from('project_volume')
                        ->join('maia_jobs', 'project_volume.volume_id', '=', 'maia_jobs.volume_id')
                        ->where('maia_jobs.id', $candidate->job_id);
                })
                ->whereIn('project_role_id', [
                    Role::editorId(),
                    Role::expertId(),
                    Role::adminId(),
                ])
                ->pluck('project_id');

            // User must be editor, expert or admin in one of the projects.
            return !empty($projectIds)
                // Label must belong to a label tree that is used by one of the projects.
                && DB::table('label_tree_project')
                    ->whereIn('project_id', $projectIds)
                    ->where('label_tree_id', $label->label_tree_id)
                    ->exists();
        });
    }
}
