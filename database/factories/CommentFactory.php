<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'commentable_id' => $this->faker->numberBetween(1, 10),
            'commentable_type' => $this->faker->randomElement([
                \App\Models\Post::class,
                // TODO: Add more model classes here in the future.
            ]),
            'creator_id' => $this->faker->numberBetween(1, 10),
            'creator_type' => \App\Models\User::class,
            'body' => $this->faker->sentence,
        ];
    }
}
