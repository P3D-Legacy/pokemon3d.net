<?php

use App\Models\GameSave;
use App\Models\User;
use App\Support\GameDataCatalogue;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

test('game data catalogue loads each name list once per locale', function () {
    GameDataCatalogue::flush();

    $catalogue = new GameDataCatalogue;

    expect($catalogue->pokemonName('1'))->toBe('Bulbasaur')
        ->and($catalogue->pokemonName('25'))->toBe('Pikachu')
        ->and(GameDataCatalogue::loadedListCount())->toBe(1);

    expect($catalogue->itemName('1'))->toBe('Masterball')
        ->and($catalogue->itemName('0'))->toBe('None')
        ->and($catalogue->abilityName('9'))->toBe('Static')
        ->and($catalogue->natureName('1'))->toBe('Lonely')
        ->and(GameDataCatalogue::loadedListCount())->toBe(4);

    $catalogue->pokemonName('4');

    expect(GameDataCatalogue::loadedListCount())->toBe(4);
});

test('game save belongs to its user', function () {
    $user = User::factory()->create();
    $save = GameSave::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($save->user())->toBeInstanceOf(BelongsTo::class)
        ->and($save->user?->is($user))->toBeTrue();
});
