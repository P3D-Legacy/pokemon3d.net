<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrNotContain implements Rule
{
    public $str;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !str_contains(strtolower($value), strtolower($this->str));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute cannot contain ' . $this->str . '.';
    }
}
