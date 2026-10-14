<?php

namespace Database\Factories;

use App\Models\PokemonMove;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PokemonMove>
 */
class PokemonMoveFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'game_id' => (string) fake()->unique()->numberBetween(1, 9000),
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'type' => 'Psychic',
            'category' => 'Status',
            'power' => null,
            'accuracy' => 100,
            'pp' => 20,
            'effect' => fake()->sentence(),
            'notes' => null,
        ];
    }
}
