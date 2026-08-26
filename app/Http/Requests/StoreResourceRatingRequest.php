<?php

namespace App\Http\Requests;

use App\Models\Resource;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $resource = Resource::query()->where('uuid', $this->route('uuid'))->first();

        return $resource !== null && ($this->user()?->can('rate', $resource) ?? false);
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:10', 'max:255'],
        ];
    }
}
