<?php

use App\Jobs\SyncGameSaveForUser;
use App\Jobs\SyncGameSaveGamejoltAccountTrophies;
use App\Listeners\Auth\UpdateUserGameJoltData;
use App\Models\GamejoltAccount;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;

it('chains game save sync before trophy sync on login', function () {
    Bus::fake();

    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    Auth::login($user->fresh());

    (new UpdateUserGameJoltData)->handle(new Login('web', $user->fresh(), false));

    Bus::assertChained([
        SyncGameSaveForUser::class,
        SyncGameSaveGamejoltAccountTrophies::class,
    ]);
});

it('does not dispatch sync jobs when the user has no GameJolt account', function () {
    Bus::fake();

    $user = User::factory()->create();

    Auth::login($user);

    (new UpdateUserGameJoltData)->handle(new Login('web', $user, false));

    Bus::assertNothingDispatched();
});
