<?php

namespace Database\Factories;

use App\Models\GamejoltAccountBan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GamejoltAccountBan>
 */
class GamejoltAccountBanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gamejoltaccount_id' => \App\Models\GamejoltAccount::factory(),
            'banned_by_id' => \App\Models\User::factory(),
            'reason_id' => \App\Models\BanReason::factory(),
            'expires_at' => $this->faker->dateTime(),
        ];
    }
}
