<?php

namespace Database\Factories;

use App\Models\PokemonAbility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PokemonAbility>
 */
class PokemonAbilityFactory extends Factory
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
            'effect' => fake()->sentence(),
            'notes' => null,
        ];
    }
}
