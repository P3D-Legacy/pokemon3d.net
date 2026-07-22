<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use App\Support\GameSavePresenter;
use Inertia\Testing\AssertableInertia as Assert;

test('game save presenter returns the full pokedex entry shape', function () {
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'pokedex' => "{1|2}\r\n{4|1}\r\n{7|0}\r\n{25|2}",
    ]);

    $payload = GameSavePresenter::forUser($user->fresh());

    expect($payload['available'])->toBeTrue()
        ->and($payload['caught_count'])->toBe(2)
        ->and($payload['seen_count'])->toBe(3)
        ->and($payload['pokedex'])->toHaveCount(4)
        ->and($payload['pokedex'][0])->toMatchArray([
            'id' => '1',
            'name' => 'Bulbasaur',
            'seen' => true,
            'caught' => true,
        ])
        ->and($payload['pokedex'][1])->toMatchArray([
            'id' => '4',
            'name' => 'Charmander',
            'seen' => true,
            'caught' => false,
        ])
        ->and($payload['pokedex'][2])->toMatchArray([
            'id' => '7',
            'name' => 'Squirtle',
            'seen' => false,
            'caught' => false,
        ])
        ->and($payload['pokedex'][3])->toMatchArray([
            'id' => '25',
            'name' => 'Pikachu',
            'seen' => true,
            'caught' => true,
        ]);
});

test('members show includes pokedex entries when a game save is available', function () {
    $user = User::factory()->create([
        'username' => 'dextrainer',
    ]);

    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
    ]);

    GameSave::factory()->create([
        'user_id' => $user->id,
        'pokedex' => "{1|2}\r\n{4|1}",
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.gameSave.available', true)
            ->where('member.gameSave.caught_count', 1)
            ->where('member.gameSave.seen_count', 2)
            ->has('member.gameSave.pokedex', 2)
            ->where('member.gameSave.pokedex.0.id', '1')
            ->where('member.gameSave.pokedex.0.name', 'Bulbasaur')
            ->where('member.gameSave.pokedex.0.seen', true)
            ->where('member.gameSave.pokedex.0.caught', true)
            ->where('member.gameSave.pokedex.1.id', '4')
            ->where('member.gameSave.pokedex.1.caught', false));
});
