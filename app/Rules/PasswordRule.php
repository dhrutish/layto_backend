<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class PasswordRule implements Rule
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
        // Password must contain at least one uppercase letter
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Password must contain at least one number
        if (!preg_match('/[0-9]/', $value)) {
            return false;
        }

        // Password must contain at least one special character (non-alphanumeric)
        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            return false;
        }

        // Password must be at least 8 characters and at most 14 characters
        if (strlen($value) < 8 || strlen($value) > 14) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must contain at least one uppercase letter, one number, one special character, and be between 8 and 14 characters long.';
    }
}
