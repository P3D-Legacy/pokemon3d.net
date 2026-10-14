<?php

namespace App\Models;

use App\Models\Concerns\RecordsWikiRevisions;
use Database\Factories\PokemonAbilityFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PokemonAbility extends BaseModel
{
    /** @use HasFactory<PokemonAbilityFactory> */
    use HasFactory;

    use RecordsWikiRevisions;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'effect',
        'notes',
    ];

    /**
     * @param  Builder<PokemonAbility>  $query
     * @return Builder<PokemonAbility>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($term): void {
            $builder->where('name', 'like', "%{$term}%")
                ->orWhere('effect', 'like', "%{$term}%")
                ->orWhere('notes', 'like', "%{$term}%");
        });
    }
}
