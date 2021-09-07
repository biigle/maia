<?php

namespace Biigle\Modules\Maia\Rules;

use Biigle\Volume;
use DB;
use Illuminate\Contracts\Validation\Rule;

class AreaKnowledgeTransferVolume implements Rule
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
            ->has('images.annotations')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('images')
                    ->whereRaw('images.volume_id = volumes.id')
                    ->whereNull(DB::raw("COALESCE(attrs->'metadata'->>'area', attrs->'laserpoints'->>'area')"));
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
        return 'The :attribute is not suited for knowledge transfer using the image area. You must be authorized to access the volume, all images must have area information and there must be annotations.';
    }
}
