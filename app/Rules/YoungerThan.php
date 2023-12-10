<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class YoungerThan implements ValidationRule
{
    private int $maxAge;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $maxAge = 90)
    {
        $this->maxAge = $maxAge;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            Carbon::now()->diff(Carbon::parse($value))->y < $this->maxAge;
        } catch (InvalidArgumentException) {
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('You need to be younger than :age years old', ['age' => $this->maxAge]);
    }
}
