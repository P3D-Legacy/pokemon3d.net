<?php

namespace App\Http\Requests\Profile;

use App\Support\EmblemCatalogue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateProfileBackgroundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'profile_background' => ['nullable', 'string', 'max:64'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $slug = $this->input('profile_background');

                if ($slug === null || $slug === '') {
                    return;
                }

                if (! is_string($slug)) {
                    $validator->errors()->add('profile_background', __('The selected profile background is invalid.'));

                    return;
                }

                $user = $this->user();

                if (! $user?->gamejolt) {
                    $validator->errors()->add(
                        'profile_background',
                        __('Link a Game Jolt account to choose a profile background.')
                    );

                    return;
                }

                if (! EmblemCatalogue::isUnlockedFor($user, $slug)) {
                    $validator->errors()->add(
                        'profile_background',
                        __('You have not unlocked that profile background.')
                    );
                }
            },
        ];
    }
}
