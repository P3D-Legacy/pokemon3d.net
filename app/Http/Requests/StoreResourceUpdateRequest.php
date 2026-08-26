<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $resource = Resource::query()->where('uuid', $this->route('uuid'))->first();

        return $resource !== null && ($this->user()?->can('postUpdate', $resource) ?? false);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'version' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5120'],
            'file' => [
                'required_without:external_download_url',
                'prohibits:external_download_url',
                'nullable',
                'file',
                'mimes:zip',
                'max:100000',
            ],
            'external_download_url' => [
                'required_without:file',
                'prohibits:file',
                'nullable',
                'url',
                'starts_with:https',
                'max:2048',
            ],
            'gameversion' => ['required', 'exists:game_versions,id'],
        ];
    }
}
