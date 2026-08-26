<?php

use App\Console\Commands\SyncPokedexFromGame;
use App\Models\Pokedex;
use Illuminate\Support\Facades\Http;

test('expand pokemon ids handles ranges regional forms unown and max', function () {
    $command = new SyncPokedexFromGame;

    expect($command->expandPokemonIds('1-3,19_alola,201;0,900-[MAX]'))->toBe([
        '1',
        '2',
        '3',
        '19_alola',
        '201;0',
        ...array_map('strval', range(900, SyncPokedexFromGame::NATIONAL_MAX)),
    ]);
});

test('sync pokedex from game stores definitions from pokedex dat', function () {
    Http::fake([
        SyncPokedexFromGame::POKEDEX_DAT_URL => Http::response(
            implode("\n", [
                'Kanto Pokédex|pokedex_kanto|1-3,25|0',
                'Sevii Pokédex|pokedex_sevii|19_alola,26_alola|0',
                'Unown Pokédex|pokedex_unown|201;0,201;1|0',
                'National Pokédex|pokedex_national|1-[MAX]|1',
            ]),
            200
        ),
    ]);

    $this->artisan('sync:pokedexfromgame')
        ->assertSuccessful();

    expect(Pokedex::query()->count())->toBe(4);

    $kanto = Pokedex::query()->where('slug', 'pokedex_kanto')->first();
    expect($kanto)->not->toBeNull()
        ->and($kanto->name)->toBe('Kanto Pokédex')
        ->and($kanto->pokemon_ids)->toBe(['1', '2', '3', '25']);

    $sevii = Pokedex::query()->where('slug', 'pokedex_sevii')->first();
    expect($sevii->pokemon_ids)->toBe(['19_alola', '26_alola']);

    $unown = Pokedex::query()->where('slug', 'pokedex_unown')->first();
    expect($unown->pokemon_ids)->toBe(['201;0', '201;1']);

    $national = Pokedex::query()->where('slug', 'pokedex_national')->first();
    expect($national->pokemon_ids)->toHaveCount(SyncPokedexFromGame::NATIONAL_MAX)
        ->and($national->pokemon_ids[0])->toBe('1')
        ->and($national->pokemon_ids[SyncPokedexFromGame::NATIONAL_MAX - 1])->toBe((string) SyncPokedexFromGame::NATIONAL_MAX);
});

test('sync pokedex from game updates existing definitions by slug', function () {
    Pokedex::factory()->create([
        'slug' => 'pokedex_kanto',
        'name' => 'Old Kanto',
        'pokemon_ids' => ['1'],
    ]);

    Http::fake([
        SyncPokedexFromGame::POKEDEX_DAT_URL => Http::response(
            'Kanto Pokédex|pokedex_kanto|1-2|0',
            200
        ),
    ]);

    $this->artisan('sync:pokedexfromgame')
        ->assertSuccessful();

    expect(Pokedex::query()->count())->toBe(1)
        ->and(Pokedex::query()->first()->name)->toBe('Kanto Pokédex')
        ->and(Pokedex::query()->first()->pokemon_ids)->toBe(['1', '2']);
});

test('sync pokedex from game fails when the remote file cannot be fetched', function () {
    Http::fake([
        SyncPokedexFromGame::POKEDEX_DAT_URL => Http::response('Nope', 500),
    ]);

    $this->artisan('sync:pokedexfromgame')
        ->assertFailed();

    expect(Pokedex::query()->count())->toBe(0);
});
