<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrMustContain implements Rule
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
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        return str_contains($value, $this->str);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute must conatin '.$this->str.'.';
    }
}
