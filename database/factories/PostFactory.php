<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'body' => $this->faker->paragraph(),
            'active' => $this->faker->boolean(),
            'sticky' => $this->faker->boolean(),
            'published_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = '+30 days'),
            'user_id' => UserFactory::new(),
        ];
    }
}
