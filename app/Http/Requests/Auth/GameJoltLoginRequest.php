<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GameJoltLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'alpha_dash', 'max:30', 'min:3'],
            'token' => ['required', 'alpha_dash', 'max:30', 'min:4'],
        ];
    }
}
