<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class OlderThan implements ValidationRule
{
    private int $minAge;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $minAge = 13)
    {
        $this->minAge = $minAge;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Carbon::now()->diff(Carbon::parse($value))->y >= $this->minAge;
        } catch (InvalidArgumentException) {
            $fail($this->message());
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
