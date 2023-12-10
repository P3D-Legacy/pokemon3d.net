<?php

namespace Database\Factories;

use App\Models\GameVersion;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResourceUpdate>
 */
class ResourceUpdateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->numberBetween(0, 2).
                '.'.
                $this->faker->numberBetween(0, 99).
                '.'.
                $this->faker->numberBetween(0, 9),
            'description' => $this->faker->paragraphs(3, true),
            'resource_id' => Resource::factory(),
            'game_version_id' => GameVersion::factory(),
            'downloads' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
