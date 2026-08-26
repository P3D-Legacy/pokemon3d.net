<?php

namespace App\Http\Requests\Save;

use Illuminate\Foundation\Http\FormRequest;

class StoreGameSaveFixRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'consent_accepted' => $this->boolean('consent_accepted'),
            'notify_database' => $this->boolean('notify_database'),
            'notify_mail' => $this->boolean('notify_mail'),
        ]);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'consent_accepted' => ['accepted'],
            'notify_database' => ['required', 'boolean'],
            'notify_mail' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array{description: string, notify_database: bool, notify_mail: bool}
     */
    public function gameSaveFixRequestData(): array
    {
        return [
            'description' => $this->validated('description'),
            'notify_database' => $this->boolean('notify_database'),
            'notify_mail' => $this->boolean('notify_mail'),
        ];
    }
}
