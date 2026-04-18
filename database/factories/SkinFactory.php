<?php

namespace Database\Factories;

use App\Models\GamejoltAccount;
use App\Models\Skin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skin>
 */
class SkinFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'public' => true,
            'user_id' => User::factory(),
            'owner_id' => 0,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Skin $skin): void {
            if ($skin->owner_id === 0) {
                $gamejolt = GamejoltAccount::factory()->create([
                    'user_id' => $skin->user_id,
                ]);
                $skin->owner_id = $gamejolt->id;
            }
        });
    }
}
