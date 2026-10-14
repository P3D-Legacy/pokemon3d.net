<?php

namespace App\Rules;

use App\Support\PublicHost;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IPHostnameARecord implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || app(PublicHost::class)->connectAddress($value) === null) {
            $fail('The :attribute must be a public hostname or IP address.');
        }
    }
}
