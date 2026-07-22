<?php

namespace Database\Factories;

use App\Models\Pokedex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pokedex>
 */
class PokedexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true).' Pokédex';

        return [
            'name' => $name,
            'slug' => str('pokedex_'.fake()->unique()->slug(2))->lower()->toString(),
            'pokemon_ids' => ['1', '4', '7', '25'],
        ];
    }
}
