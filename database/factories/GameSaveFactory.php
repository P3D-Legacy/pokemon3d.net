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
            'party' => implode("\r\n", [
                '{"Pokemon"[25]}{"Experience"[1000.00]}{"Gender"[0]}{"EggSteps"[0]}{"Item"[0]}{"NickName"[Sparky]}{"Level"[15]}{"OT"[12345]}{"Ability"[9]}{"Status"[]}{"Nature"[0]}{"CatchLocation"[Route 1]}{"CatchTrainer"[Red]}{"CatchBall"[5]}{"CatchMethod"[caught at]}{"Friendship"[70]}{"isShiny"[0]}',
                '{"Pokemon"[1]}{"Experience"[100.00]}{"Gender"[1]}{"EggSteps"[14]}{"Item"[0]}{"NickName"[]}{"Level"[1]}{"OT"[12345]}{"Ability"[65]}{"Status"[]}{"Nature"[1]}{"CatchLocation"[Pokemon Center]}{"CatchTrainer"[Red]}{"CatchBall"[5]}{"CatchMethod"[obtained at]}{"Friendship"[70]}{"isShiny"[1]}',
            ]),
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
            'statistics' => implode("\r\n", [
                '{Steps[0],12345.00',
                '{BattlesWon[1],100.00',
                '{PokemonCaught[2],50.00',
                '{PlayTime[3],3600.00',
            ]),
        ];
    }
}
