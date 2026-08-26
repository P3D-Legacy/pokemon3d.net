<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
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
                Post::class,
                // TODO: Add more model classes here in the future.
            ]),
            'creator_id' => $this->faker->numberBetween(1, 10),
            'creator_type' => User::class,
            'body' => $this->faker->sentence(),
        ];
    }
}
