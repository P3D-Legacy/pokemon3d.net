<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberIndexRequest extends FormRequest
{
    public const DEFAULT_SORT = 'last_active';

    /**
     * @var list<string>
     */
    public const SORTS = [
        'last_active',
        'joined',
        'joined_oldest',
        'username',
        'username_desc',
    ];

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
            'search' => ['nullable', 'string', 'max:100'],
            'sort' => ['nullable', 'string'],
            'gamejolt' => ['nullable'],
            'gamesave' => ['nullable'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $search = $this->input('search');

        if (is_string($search)) {
            $this->merge([
                'search' => mb_substr(trim($search), 0, 100),
            ]);
        }
    }

    public function search(): string
    {
        return (string) $this->validated('search', '');
    }

    public function sort(): string
    {
        $sort = (string) ($this->input('sort') ?? self::DEFAULT_SORT);

        return in_array($sort, self::SORTS, true) ? $sort : self::DEFAULT_SORT;
    }

    public function hasGamejolt(): bool
    {
        return $this->boolean('gamejolt');
    }

    public function hasGamesave(): bool
    {
        return $this->boolean('gamesave');
    }

    /**
     * @return array{search: string, sort: string, gamejolt: bool, gamesave: bool}
     */
    public function filters(): array
    {
        return [
            'search' => $this->search(),
            'sort' => $this->sort(),
            'gamejolt' => $this->hasGamejolt(),
            'gamesave' => $this->hasGamesave(),
        ];
    }
}
