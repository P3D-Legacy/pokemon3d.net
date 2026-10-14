<?php

namespace App\Http\Requests;

use App\Models\WikiPage;
use Illuminate\Foundation\Http\FormRequest;

class StoreWikiPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', WikiPage::class) ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:3'],
            'published' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
        ];
    }
}
