<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LinkGamejoltAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return filled(config('services.gamejolt.game_id'))
            && filled(config('services.gamejolt.private_key'));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'alpha_dash',
                'max:30',
                'min:3',
                Rule::unique('game_jolt_accounts', 'username')->ignore($this->user()?->id, 'user_id'),
            ],
            'token' => ['required', 'alpha_dash', 'max:30', 'min:4'],
        ];
    }
}
