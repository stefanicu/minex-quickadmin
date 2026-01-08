<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
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
        // The slug can include lowercase letters, numbers, hyphens, and underscores
        return preg_match('/^(?!-|_)[a-z0-9-_]+(?<!-|_)$/', $value);
    }
    
    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid slug (lowercase, numbers, hyphens, and underscores only).';
    }
}

