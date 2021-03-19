<?php

namespace Biigle\Modules\Maia\Policies;

use Biigle\Modules\Maia\TrainingProposal;
use Biigle\Policies\CachedPolicy;
use Biigle\Role;
use Biigle\User;
use Cache;
use DB;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingProposalPolicy extends CachedPolicy
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
        $only = ['access'];

        if ($user->can('sudo') && in_array($ability, $only)) {
            return true;
        }
    }

    /**
     * Determine if the given training proposal can be accessed by the user.
     *
     * @param  User  $user
     * @param  TrainingProposal  $proposal
     * @return bool
     */
    public function access(User $user, TrainingProposal $proposal)
    {
        // Put this to persistent cache for rapid querying of proposal patches.
        return Cache::remember("maia-proposal-can-access-{$user->id}-{$proposal->job_id}", 30, function () use ($user, $proposal) {
            // Check if user is editor, expert or admin of one of the projects, the proposal belongs to.
            return DB::table('project_user')
                ->where('user_id', $user->id)
                ->whereIn('project_id', function ($query) use ($proposal) {
                    $query->select('project_id')
                        ->from('project_volume')
                        ->join('maia_jobs', 'project_volume.volume_id', '=', 'maia_jobs.volume_id')
                        ->where('maia_jobs.id', $proposal->job_id);
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
     * Determine if the given user can update the training proposal.
     *
     * @param User $user
     * @param TrainingProposal $proposal
     *
     * @return bool
     */
    public function update(User $user, TrainingProposal $proposal)
    {
        return $this->access($user, $proposal);
    }
}
