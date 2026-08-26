<?php

namespace Database\Factories;

use App\Models\GameVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameVersion>
 */
class GameVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $version =
            $this->faker->numberBetween(0, 2).
            '.'.
            $this->faker->numberBetween(0, 99).
            '.'.
            $this->faker->numberBetween(0, 9);

        return [
            'version' => $version,
            'title' => 'Release '.$version,
            'release_date' => $this->faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now'),
            'page_url' => '#',
            'download_url' => '#',
            'post_id' => PostFactory::new(),
        ];
    }
}
