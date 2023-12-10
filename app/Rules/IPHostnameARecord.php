<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IPHostnameARecord implements ValidationRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $ipValid = filter_var($value, FILTER_VALIDATE_IP) !== false;
        $domainValid = filter_var($value, FILTER_VALIDATE_DOMAIN) !== false;
        $aRecord = checkdnsrr($value, 'A');

        if (! $ipValid && ! $domainValid && ! $aRecord) {
            $fail($this->message());
        }
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return ':Attribute does not have a valid hostname/ip format or does not return an A record.';
    }
}
