<?php

namespace Database\Factories;

use App\Models\BanReason;
use App\Models\GamejoltAccount;
use App\Models\GamejoltAccountBan;
use App\Models\User;
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
            'gamejoltaccount_id' => GamejoltAccount::factory(),
            'banned_by_id' => User::factory(),
            'reason_id' => BanReason::factory(),
            'expires_at' => $this->faker->dateTime(),
        ];
    }
}
