<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Server>
 */
class ServerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->optional()->sentence(),
            'host' => $this->faker->domainName(),
            'port' => $this->faker->numberBetween(1, 65535),
            'active' => $this->faker->boolean(),
            'last_check_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            'user_id' => UserFactory::new(),
            'ping' => $this->faker->optional()->numberBetween(1, 100),
            'official' => $this->faker->boolean(10),
        ];
    }
}
