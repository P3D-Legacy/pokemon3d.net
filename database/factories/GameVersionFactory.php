<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameVersion>
 */
class GameVersionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\GameVersion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $version = $this->faker->numberBetween(0, 2).'.'.$this->faker->numberBetween(0, 99).'.'.$this->faker->numberBetween(0, 9);
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
