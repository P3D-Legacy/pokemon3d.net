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
        $pikachu = '{"Pokemon"[25]}{"Experience"[1000.00]}{"Gender"[0]}{"EggSteps"[0]}{"Item"[0]}{"NickName"[Sparky]}{"Level"[15]}{"OT"[12345]}{"Ability"[9]}{"Status"[]}{"Nature"[0]}{"CatchLocation"[Route 1]}{"CatchTrainer"[Red]}{"CatchBall"[5]}{"CatchMethod"[caught at]}{"Friendship"[70]}{"isShiny"[0]}';
        $bulbasaur = '{"Pokemon"[1]}{"Experience"[100.00]}{"Gender"[1]}{"EggSteps"[0]}{"Item"[0]}{"NickName"[]}{"Level"[5]}{"OT"[12345]}{"Ability"[65]}{"Status"[]}{"Nature"[1]}{"CatchLocation"[Pokemon Center]}{"CatchTrainer"[Red]}{"CatchBall"[5]}{"CatchMethod"[obtained at]}{"Friendship"[70]}{"isShiny"[0]}';
        $egg = '{"Pokemon"[1]}{"Experience"[100.00]}{"Gender"[1]}{"EggSteps"[14]}{"Item"[0]}{"NickName"[]}{"Level"[1]}{"OT"[12345]}{"Ability"[65]}{"Status"[]}{"Nature"[1]}{"CatchLocation"[Pokemon Center]}{"CatchTrainer"[Red]}{"CatchBall"[5]}{"CatchMethod"[obtained at]}{"Friendship"[70]}{"isShiny"[1]}';
        $hofMon = '{"Pokemon"[25]}{"Gender"[0]}{"NickName"[Sparky]}{"Level"[50]}{"OT"[12345]}{"CatchTrainer"[Red]}{"isShiny"[0]}{"AdditionalData"[]}';

        return [
            'user_id' => User::factory(),
            'apricorns' => implode("\r\n", [
                '{johto\routes\route29.dat|10,0,5|2024,1,15,12,0,0}',
                '{Kurt|2,1,0,0,0,0,1|2024,1,16,8,30,0}',
            ]),
            'berries' => '{johto\routes\route29.dat|12,0,8|2000,2,1|2024,2,1,10,0,0}',
            'box' => implode("\r\n", [
                '0,0,'.$bulbasaur,
                '0,1,'.$pikachu,
                '1,0,'.$egg,
            ]),
            'daycare' => implode("\r\n", [
                '0,0,'.$bulbasaur,
                '0,Egg,133',
            ]),
            'halloffame' => implode("\r\n", [
                '0,'.$hofMon,
                '0,(Red|10:00:00|150|12345|Red)',
            ]),
            'itemdata' => 'johto\towns\newbark.dat|5,johto\routes\route29.dat|4',
            'items' => implode("\r\n", [
                '{5|10}',
                '{2|3}',
                '{38|1}',
            ]),
            'npc' => '',
            'options' => '',
            'party' => implode("\r\n", [
                $pikachu,
                $egg,
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
            'roamingpokemon' => '100|243|40|0|johto\routes\route42.dat|roaming|0|'.$hofMon,
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
