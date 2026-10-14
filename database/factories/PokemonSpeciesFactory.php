<?php

namespace Database\Factories;

use App\Models\PokemonSpecies;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PokemonSpecies>
 */
class PokemonSpeciesFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->firstName();

        return [
            'game_id' => (string) fake()->unique()->numberBetween(1, 9000),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'type_1' => 'Psychic',
            'type_2' => null,
            'hp' => 25,
            'attack' => 20,
            'defense' => 15,
            'special_attack' => 105,
            'special_defense' => 55,
            'speed' => 90,
            'ability_1' => '28',
            'ability_2' => '39',
            'hidden_ability' => '98',
            'height' => 0.9,
            'weight' => 19.5,
            'species' => 'Psi Pokémon',
            'pokedex_entry' => fake()->sentence(),
            'moves' => [['level' => 1, 'move_id' => '100']],
            'egg_moves' => ['502'],
            'machines' => ['223'],
            'evolution' => ['evolves_to' => '64', 'method' => 'Level', 'value' => '16'],
            'notes' => null,
        ];
    }
}
