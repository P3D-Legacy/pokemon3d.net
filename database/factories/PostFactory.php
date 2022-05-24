<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'body' => $this->faker->paragraphs(5),
            'active' => $this->faker->boolean,
            'sticky' => $this->faker->boolean,
            'published_at' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = '+30 days'),
            'user_id' => UserFactory::new(),
        ];
    }
}
