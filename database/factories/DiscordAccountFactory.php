<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiscordAccount>
 */
class DiscordAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'username' => $this->faker->userName(),
            'email' => $this->faker->email(),
            'avatar' => $this->faker->imageUrl(),
            'discriminator' => $this->faker->randomNumber(4),
            'verified_at' => $this->faker->dateTime(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
