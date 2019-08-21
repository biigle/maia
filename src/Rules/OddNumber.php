<?php

namespace Biigle\Modules\Maia\Rules;

use Illuminate\Contracts\Validation\Rule;

class OddNumber implements Rule
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
        return $value % 2 !== 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be an odd number.';
    }
}
