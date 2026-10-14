<?php

namespace App\Models;

use App\Models\Concerns\RecordsWikiRevisions;
use Database\Factories\PokemonMoveFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PokemonMove extends BaseModel
{
    /** @use HasFactory<PokemonMoveFactory> */
    use HasFactory;

    use RecordsWikiRevisions;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'type',
        'category',
        'power',
        'accuracy',
        'pp',
        'effect',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'power' => 'integer',
            'accuracy' => 'integer',
            'pp' => 'integer',
        ];
    }

    /**
     * @param  Builder<PokemonMove>  $query
     * @return Builder<PokemonMove>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('name', 'like', "%{$term}%")
                ->orWhere('effect', 'like', "%{$term}%")
                ->orWhere('notes', 'like', "%{$term}%")
                ->orWhere('type', 'like', "%{$term}%");
        });
    }
}
