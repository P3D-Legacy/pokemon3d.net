<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug(),
            'body' => $this->faker->paragraphs($this->faker->numberBetween(1, 99), true),
            'active' => $this->faker->boolean(90),
            'sticky' => $this->faker->boolean(10),
            'published_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = '+30 days'),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
