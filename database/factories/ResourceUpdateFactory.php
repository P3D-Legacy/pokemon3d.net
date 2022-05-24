<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResourceUpdate>
 */
class ResourceUpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\ResourceUpdate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->optional()->paragraph,
            'resource_id' => ResourceFactory::new(),
            'game_version_id' => GameVersionFactory::new(),
            'downloads' => $this->faker->numberBetween(0, 10000),
        ];
    }
}
