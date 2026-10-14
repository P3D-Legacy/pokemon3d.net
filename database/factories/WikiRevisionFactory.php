<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\WikiPage;
use App\Models\WikiRevision;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WikiRevision>
 */
class WikiRevisionFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $page = WikiPage::factory();

        return [
            'revisionable_type' => WikiPage::class,
            'revisionable_id' => $page,
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'body' => fake()->paragraphs(2, true),
            'snapshot' => ['title' => fake()->sentence(3)],
            'summary' => fake()->optional()->sentence(),
        ];
    }
}
