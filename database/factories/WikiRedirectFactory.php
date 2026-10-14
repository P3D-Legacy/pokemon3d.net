<?php

namespace Database\Factories;

use App\Models\WikiPage;
use App\Models\WikiRedirect;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WikiRedirect>
 */
class WikiRedirectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'from_slug' => Str::slug(fake()->unique()->words(2, true)),
            'redirectable_type' => WikiPage::class,
            'redirectable_id' => WikiPage::factory(),
        ];
    }
}
