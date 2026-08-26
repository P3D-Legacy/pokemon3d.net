<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Throwable;

class SpamMail implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (! app('spammailchecker')->validate((string) $value)) {
                $fail(__('validation.spam_mail'));
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
