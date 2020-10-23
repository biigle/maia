<?php

namespace Biigle\Modules\Maia\Rules;

use Biigle\Volume;
use DB;
use Illuminate\Contracts\Validation\Rule;

class KnowledgeTransferVolume implements Rule
{
    /**
      * Determine if the validation rule passes.
      *
      * @param  string  $attribute
      * @param  mixed  $value
      * @return bool
      */
    public function passes($attribute, $value)
    {
        return Volume::accessibleBy(request()->user())
            ->where('id', $value)
            ->whereHas('images')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('images')
                    ->whereRaw('images.volume_id = volumes.id')
                    ->whereNull('attrs->metadata->distance_to_ground');
            })
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute is not suited for knowledge transfer. You must be authorized to access the volume and all images must have distance to ground information.';
    }
}
