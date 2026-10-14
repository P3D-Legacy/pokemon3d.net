<?php

namespace App\Models;

use App\Models\Concerns\RecordsWikiRevisions;
use Database\Factories\PokemonItemFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PokemonItem extends BaseModel
{
    /** @use HasFactory<PokemonItemFactory> */
    use HasFactory;

    use RecordsWikiRevisions;

    protected $fillable = [
        'game_id',
        'name',
        'slug',
        'category',
        'buy_price',
        'sell_price',
        'effect',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'buy_price' => 'integer',
            'sell_price' => 'integer',
        ];
    }

    /**
     * @param  Builder<PokemonItem>  $query
     * @return Builder<PokemonItem>
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
                ->orWhere('category', 'like', "%{$term}%");
        });
    }
}
