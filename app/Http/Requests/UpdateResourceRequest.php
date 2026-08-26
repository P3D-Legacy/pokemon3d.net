<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $resource = Resource::query()->where('uuid', $this->route('uuid'))->first();

        return $resource !== null && ($this->user()?->can('update', $resource) ?? false);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'brief' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:5120'],
            'category' => ['required', 'exists:categories,id'],
        ];
    }
}
