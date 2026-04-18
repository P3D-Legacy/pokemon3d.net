<?php

namespace Database\Factories;

use App\Models\GamejoltAccount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GamejoltAccount>
 */
class GamejoltAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->unique()->numberBetween(100_000, 999_999_999),
            'username' => $this->faker->userName(),
            'token' => $this->faker->uuid(),
            'verified_at' => $this->faker->dateTime(),
            'user_id' => User::factory(),
        ];
    }
}
