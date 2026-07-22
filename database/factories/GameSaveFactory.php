<?php

namespace Database\Factories;

use App\Models\GameSave;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GameSave>
 */
class GameSaveFactory extends Factory
{
    protected $model = GameSave::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'apricorns' => '',
            'berries' => '',
            'box' => '',
            'daycare' => '',
            'halloffame' => '',
            'itemdata' => '',
            'items' => '',
            'npc' => '',
            'options' => '',
            'party' => '',
            'player' => implode("\r\n", [
                'Name|Red',
                'RivalName|Blue',
                'Location|Pallet Town',
                'Money|3000',
                'HasPokedex|1',
                'HasPokegear|0',
                'SaveCreated|01.01.2020',
                'Gender|Male',
                'OT|12345',
                'Points|150',
                'GTSStars|3',
            ]),
            'pokedex' => "{1|2}\r\n{4|1}\r\n{7|0}",
            'register' => '',
            'roamingpokemon' => '',
            'secretbase' => '',
            'statistics' => '',
        ];
    }
}
