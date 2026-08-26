<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\Pokedex;
use App\Models\User;
use App\Support\GameSavePresenter;

test('game save resolves regional and unown form names', function () {
    $gamesave = GameSave::factory()->create([
        'pokedex' => "{19_alola|2}\r\n{201;0|1}",
    ]);

    expect($gamesave->getPokemonName('19_alola'))->toBe('Alolan Rattata')
        ->and($gamesave->getPokemonName('201;0'))->toBe('Unown A')
        ->and($gamesave->getPokemonName('9999_missing'))->toBe('???');
});

test('get pokedex by ids preserves definition order and fills missing entries', function () {
    $gamesave = GameSave::factory()->create([
        'pokedex' => "{4|1}\r\n{19_alola|2}",
    ]);

    $entries = $gamesave->getPokedexByIds(['19_alola', '1', '4']);

    expect($entries)->toHaveCount(3)
        ->and($entries[0])->toMatchArray([
            'id' => '19_alola',
            'name' => 'Alolan Rattata',
            'seen' => true,
            'caught' => true,
        ])
        ->and($entries[1])->toMatchArray([
            'id' => '1',
            'name' => 'Bulbasaur',
            'seen' => false,
            'caught' => false,
        ])
        ->and($entries[2])->toMatchArray([
            'id' => '4',
            'name' => 'Charmander',
            'seen' => true,
            'caught' => false,
        ]);
});

test('game save presenter returns multi dex payload with per dex counts', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'pokedex' => "{1|2}\r\n{19_alola|1}\r\n{25|2}",
    ]);

    Pokedex::factory()->create([
        'name' => 'Kanto Pokédex',
        'slug' => 'pokedex_kanto',
        'pokemon_ids' => ['1', '25', '4'],
    ]);

    Pokedex::factory()->create([
        'name' => 'Sevii Pokédex',
        'slug' => 'pokedex_sevii',
        'pokemon_ids' => ['19_alola', '26_alola'],
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['available'])->toBeTrue()
        ->and($payload['caught_count'])->toBe(2)
        ->and($payload['seen_count'])->toBe(3)
        ->and($payload)->not->toHaveKey('pokedex')
        ->and($payload['pokedexes'])->toHaveCount(2)
        ->and($payload['pokedexes'][0])->toMatchArray([
            'slug' => 'pokedex_kanto',
            'name' => 'Kanto Pokédex',
            'caught_count' => 2,
            'seen_count' => 2,
        ])
        ->and($payload['pokedexes'][0]['entries'])->toHaveCount(3)
        ->and($payload['pokedexes'][0]['entries'][0])->toMatchArray([
            'id' => '1',
            'name' => 'Bulbasaur',
            'seen' => true,
            'caught' => true,
        ])
        ->and($payload['pokedexes'][0]['entries'][2])->toMatchArray([
            'id' => '4',
            'name' => 'Charmander',
            'seen' => false,
            'caught' => false,
        ])
        ->and($payload['pokedexes'][1])->toMatchArray([
            'slug' => 'pokedex_sevii',
            'name' => 'Sevii Pokédex',
            'caught_count' => 0,
            'seen_count' => 1,
        ])
        ->and($payload['pokedexes'][1]['entries'][0])->toMatchArray([
            'id' => '19_alola',
            'name' => 'Alolan Rattata',
            'seen' => true,
            'caught' => false,
        ]);
});
