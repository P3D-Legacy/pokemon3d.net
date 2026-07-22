<?php

namespace Database\Factories;

use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountTrophy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GamejoltAccountTrophy>
 */
class GamejoltAccountTrophyFactory extends Factory
{
    protected $model = GamejoltAccountTrophy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(1, 999_999),
            'title' => $this->faker->words(2, true),
            'difficulty' => $this->faker->randomElement(['Bronze', 'Silver', 'Gold', 'Platinum']),
            'description' => $this->faker->sentence(),
            'image_url' => 'https://example.com/trophy.png',
            'achieved' => false,
            'gamejolt_account_id' => GamejoltAccount::factory(),
        ];
    }

    public function achieved(): static
    {
        return $this->state(fn (): array => [
            'achieved' => true,
        ]);
    }
}
