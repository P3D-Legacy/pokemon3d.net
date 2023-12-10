<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;
use InvalidArgumentException;

class OlderThan implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->minAge = 13;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  mixed  $value
     */
    public function passes(string $attribute, $value): bool
    {
        try {
            return Carbon::now()->diff(Carbon::parse($value))->y >= $this->minAge;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('You need to be older than :age years old', ['age' => $this->minAge]);
    }
}
