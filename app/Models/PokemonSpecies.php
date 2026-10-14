<?php

namespace App\Models;

use App\Models\Concerns\RecordsWikiRevisions;
use Database\Factories\PokemonSpeciesFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PokemonSpecies extends BaseModel
{
    /** @use HasFactory<PokemonSpeciesFactory> */
    use HasFactory;

    use RecordsWikiRevisions;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'type_1',
        'type_2',
        'hp',
        'attack',
        'defense',
        'special_attack',
        'special_defense',
        'speed',
        'ability_1',
        'ability_2',
        'hidden_ability',
        'height',
        'weight',
        'species',
        'pokedex_entry',
        'moves',
        'egg_moves',
        'machines',
        'evolution',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hp' => 'integer',
            'attack' => 'integer',
            'defense' => 'integer',
            'special_attack' => 'integer',
            'special_defense' => 'integer',
            'speed' => 'integer',
            'height' => 'float',
            'weight' => 'float',
            'moves' => 'array',
            'egg_moves' => 'array',
            'machines' => 'array',
            'evolution' => 'array',
        ];
    }

    /**
     * @param  Builder<PokemonSpecies>  $query
     * @return Builder<PokemonSpecies>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('name', 'like', "%{$term}%")
                ->orWhere('pokedex_entry', 'like', "%{$term}%")
                ->orWhere('notes', 'like', "%{$term}%")
                ->orWhere('type_1', 'like', "%{$term}%")
                ->orWhere('type_2', 'like', "%{$term}%");
        });
    }
}
