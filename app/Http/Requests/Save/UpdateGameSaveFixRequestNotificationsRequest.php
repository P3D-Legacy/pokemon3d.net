<?php

namespace App\Http\Requests\Save;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGameSaveFixRequestNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $request = $this->route('fix_request');

        return $this->user()?->is($request->user) ?? false;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
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
            'notify_database' => ['required', 'boolean'],
            'notify_mail' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array{notify_database: bool, notify_mail: bool}
     */
    public function notificationPreferences(): array
    {
        return [
            'notify_database' => $this->boolean('notify_database'),
            'notify_mail' => $this->boolean('notify_mail'),
        ];
    }
}
