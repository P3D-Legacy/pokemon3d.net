<?php

namespace App\Models;

use Database\Factories\PokedexFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pokedex extends BaseModel
{
    /** @use HasFactory<PokedexFactory> */
    use HasFactory;

    protected $table = 'pokedex';

    protected $fillable = [
        'name',
        'slug',
        'pokemon_ids',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pokemon_ids' => 'array',
        ];
    }
}
