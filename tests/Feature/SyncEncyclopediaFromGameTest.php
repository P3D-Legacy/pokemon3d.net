<?php

use App\Console\Commands\SyncEncyclopediaFromGame;
use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use Illuminate\Support\Facades\Http;

test('encyclopedia sync from catalogues creates abilities items and pokemon', function () {
    $this->artisan('sync:encyclopedia', ['--skip-dat' => true])
        ->assertSuccessful();

    expect(PokemonAbility::query()->where('name', 'Stench')->exists())->toBeTrue()
        ->and(PokemonItem::query()->where('name', 'Masterball')->exists())->toBeTrue()
        ->and(PokemonSpecies::query()->where('name', 'Bulbasaur')->exists())->toBeTrue();
});

test('encyclopedia sync parses pokemon dat files', function () {
    Http::fake([
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Pokemon/Data/1.dat' => Http::response(
            implode("\n", [
                'Name|Bulbasaur',
                'Type1|Grass',
                'Type2|Poison',
                'BaseHP|45',
                'BaseAttack|49',
                'BaseDefense|49',
                'BaseSpAttack|65',
                'BaseSpDefense|65',
                'BaseSpeed|45',
                'Ability1|65',
                'Ability2|',
                'HiddenAbility|34',
                'Pokedex|A strange seed was planted on its back.\Seed Pokémon\6.9\0.7\0,0,0',
                'Move|1,33',
                'EggMoves|22,73',
                'Machines|15',
                'EvolutionCondition|2,Level,16,Level',
            ]),
            200
        ),
    ]);

    $this->artisan('sync:encyclopedia', ['--limit' => 1])
        ->assertSuccessful();

    $bulbasaur = PokemonSpecies::query()->where('game_id', '1')->first();

    expect($bulbasaur)->not->toBeNull()
        ->and($bulbasaur->type_1)->toBe('Grass')
        ->and($bulbasaur->type_2)->toBe('Poison')
        ->and($bulbasaur->hp)->toBe(45)
        ->and($bulbasaur->species)->toBe('Seed Pokémon')
        ->and($bulbasaur->evolution['evolves_to'])->toBe('2')
        ->and(PokemonMove::query()->where('game_id', '33')->exists())->toBeTrue();
});

test('pokemon dat parser extracts structured fields', function () {
    $command = new SyncEncyclopediaFromGame;
    $parsed = $command->parsePokemonDat("Type1|Psychic\nType2|\nBaseHP|25\nPokedex|It teleports.\\Psi Pokémon\\19.5\\0.9\\0\nMove|1,100\n");

    expect($parsed['type_1'])->toBe('Psychic')
        ->and($parsed['hp'])->toBe(25)
        ->and($parsed['species'])->toBe('Psi Pokémon')
        ->and($parsed['moves'][0]['move_id'])->toBe('100');
});
