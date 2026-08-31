<?php

namespace App\Rules;

use App\Support\PokemonCaptcha;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PokemonCaptchaAnswers implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value) || ! PokemonCaptcha::verify($value)) {
            $fail(__('validation.pokemon_captcha'));
        }
    }
}
