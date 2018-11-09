<?php

namespace Biigle\Modules\Maia\Policies;

use DB;
use Cache;
use Biigle\Role;
use Biigle\User;
use Biigle\Modules\Maia\MaiaAnnotation;
use Biigle\Policies\CachedPolicy;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaiaAnnotationPolicy extends CachedPolicy
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
     * Determine if the given annotation can be accessed by the user.
     *
     * @param  User  $user
     * @param  MaiaAnnotation  $annotation
     * @return bool
     */
    public function access(User $user, MaiaAnnotation $annotation)
    {
        // Put this to persistent cache for rapid querying of annotation patches.
        return Cache::remember("maia-annotation-can-access-{$user->id}-{$annotation->job_id}", 0.5,function () use ($user, $annotation) {
            // check if user is editor, expert or admin of one of the projects, the annotation belongs to
            return DB::table('project_user')
                ->where('user_id', $user->id)
                ->whereIn('project_id', function ($query) use ($annotation) {
                    $query->select('project_id')
                        ->from('project_volume')
                        ->join('maia_jobs', 'project_volume.volume_id', '=', 'maia_jobs.volume_id')
                        ->where('maia_jobs.id', $annotation->job_id);
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
     * Determine if the given user can update the MAIA annotation.
     *
     * @param User $user
     * @param MaiaAnnotation $annotation
     *
     * @return bool
     */
    public function update(User $user, MaiaAnnotation $annotation)
    {
        return $this->access($user, $annotation);
    }
}
