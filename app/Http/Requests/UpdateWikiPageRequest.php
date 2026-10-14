<?php

namespace App\Http\Requests;

use App\Models\WikiPage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWikiPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        $page = $this->route('wikiPage');

        if ($page instanceof WikiPage) {
            return $this->user()?->can('update', $page) ?? false;
        }

        return $this->user()?->can('wiki.update') ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'min:2', 'max:255'],
            'body' => ['sometimes', 'required', 'string', 'min:3'],
            'published' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'type_1' => ['nullable', 'string', 'max:50'],
            'type_2' => ['nullable', 'string', 'max:50'],
            'type' => ['nullable', 'string', 'max:50'],
            'category' => ['nullable', 'string', 'max:100'],
            'hp' => ['nullable', 'integer', 'min:0', 'max:255'],
            'attack' => ['nullable', 'integer', 'min:0', 'max:255'],
            'defense' => ['nullable', 'integer', 'min:0', 'max:255'],
            'special_attack' => ['nullable', 'integer', 'min:0', 'max:255'],
            'special_defense' => ['nullable', 'integer', 'min:0', 'max:255'],
            'speed' => ['nullable', 'integer', 'min:0', 'max:255'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'species' => ['nullable', 'string', 'max:255'],
            'pokedex_entry' => ['nullable', 'string'],
            'power' => ['nullable', 'integer', 'min:0', 'max:999'],
            'accuracy' => ['nullable', 'integer', 'min:0', 'max:100'],
            'pp' => ['nullable', 'integer', 'min:0', 'max:99'],
            'effect' => ['nullable', 'string'],
            'buy_price' => ['nullable', 'integer', 'min:0'],
            'sell_price' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
