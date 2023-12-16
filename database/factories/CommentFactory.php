<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'commentable_id' => $this->faker->numberBetween(1, 10),
            'commentable_type' => $this->faker->randomElement([
                \App\Models\Post::class,
                // TODO: Add more model classes here in the future.
            ]),
            'creator_id' => $this->faker->numberBetween(1, 10),
            'creator_type' => \App\Models\User::class,
            'body' => $this->faker->sentence(),
        ];
    }
}
