<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(10)
                ->mixedCase()
                ->numbers()
                ->symbols(),
            'confirmed',
        ];
    }
}
