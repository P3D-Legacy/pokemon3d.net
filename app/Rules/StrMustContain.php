<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrMustContain implements ValidationRule
{
    public string $str;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $str)
    {
        $this->str = $str;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_contains($value, $this->str)) {
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return 'The :attribute must contain '.$this->str.'.';
    }
}
