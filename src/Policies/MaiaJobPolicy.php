<?php

namespace Biigle\Modules\Maia\Policies;

use DB;
use Biigle\Role;
use Biigle\User;
use Biigle\Modules\Maia\MaiaJob;
use Biigle\Policies\CachedPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaiaJobPolicy extends CachedPolicy
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
     * Determine if the given job can be accessed by the user.
     *
     * @param  User  $user
     * @param  MaiaJob  $job
     * @return bool
     */
    public function access(User $user, MaiaJob $job)
    {
        return $this->remember("maia-can-access-{$user->id}-{$job->volume_id}", function () use ($user, $job) {
            // check if user is editor, expert or admin of one of the projects, the job belongs to
            return DB::table('project_user')
                ->where('user_id', $user->id)
                ->whereIn('project_id', function ($query) use ($job) {
                    $query->select('project_id')
                        ->from('project_volume')
                        ->where('volume_id', $job->volume_id);
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
     * Determine if the given user can update the MAIA job.
     *
     * @param User $user
     * @param MaiaJob $job
     *
     * @return bool
     */
    public function update(User $user, MaiaJob $job)
    {
        return $this->access($user, $job);
    }

    /**
     * Determine if the given user can destroy the MAIA job.
     *
     * @param User $user
     * @param MaiaJob $job
     *
     * @return bool
     */
    public function destroy(User $user, MaiaJob $job)
    {
        return $this->access($user, $job);
    }
}
