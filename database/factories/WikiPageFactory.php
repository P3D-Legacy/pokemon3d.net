<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WikiPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WikiPage>
 */
class WikiPageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('###'),
            'body' => "## {$title}\n\n".fake()->paragraphs(3, true),
            'excerpt' => fake()->sentence(),
            'published' => true,
            'user_id' => User::factory(),
        ];
    }

    public function unpublished(): static
    {
        return $this->state(fn (): array => [
            'published' => false,
        ]);
    }
}
