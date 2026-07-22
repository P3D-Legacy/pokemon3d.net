<?php

use App\Jobs\SyncGameSaveForUser;
use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;

it('aborts without writing a game save when the user has no linked GameJolt account', function () {
    $user = User::factory()->create();

    (new SyncGameSaveForUser($user))->handle();

    expect(GameSave::where('user_id', $user->id)->exists())->toBeFalse();
});

it('aborts without writing a game save when GameJolt credentials are missing', function () {
    config([
        'services.gamejolt.game_id' => null,
        'services.gamejolt.private_key' => null,
    ]);

    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    (new SyncGameSaveForUser($user->fresh()))->handle();

    expect(GameSave::where('user_id', $user->id)->exists())->toBeFalse();
});

it('fails the sync command when the GameJolt account does not exist', function () {
    $this->artisan('sync:gamesave', ['gamejolt_user_id' => '999999999'])
        ->expectsOutput('GameJolt account [999999999] not found.')
        ->assertFailed();
});
