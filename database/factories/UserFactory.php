<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => $this->faker->unique()->userName(),
            'about' => $this->faker->optional()->sentence(),
            'gender' => $this->faker->numberBetween(0, 3),
            'location' => $this->faker->city().', '.$this->faker->country(),
            'birthdate' => $this->faker->date($max = Carbon::now()->subYears(13)),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'timezone' => $this->faker->timezone(),
            'locale' => $this->faker->locale(),
            'last_active_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that the user should have a personal team.
     */
    public function withPersonalTeam(): static
    {
        if (! Features::hasTeamFeatures()) {
            return $this->state([]);
        }

        return $this->has(
            Team::factory()->state(function (array $attributes, User $user) {
                return [
                    'name' => $user->name.'\'s Team',
                    'user_id' => $user->id,
                    'personal_team' => true,
                ];
            }),
            'ownedTeams'
        );
    }
}
