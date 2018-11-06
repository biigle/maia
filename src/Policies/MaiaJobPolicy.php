<?php

namespace Biigle\Modules\Maia\Policies;

use Biigle\User;
use Biigle\Modules\Maia\MaiaJob;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaiaJobPolicy
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

    // /**
    //  * Determine if the given assistance request can be accessed by the user.
    //  *
    //  * @param  User  $user
    //  * @param  MaiaJob  $job
    //  * @return bool
    //  */
    // public function access(User $user, MaiaJob $job)
    // {
    //     return $job->user_id === $user->id;
    // }

    // /**
    //  * Determine if the given assistance request can be deleted by the user.
    //  *
    //  * @param  User  $user
    //  * @param  MaiaJob  $job
    //  * @return bool
    //  */
    // public function destroy(User $user, MaiaJob $job)
    // {
    //     return $this->access($user, $job);
    // }
}
