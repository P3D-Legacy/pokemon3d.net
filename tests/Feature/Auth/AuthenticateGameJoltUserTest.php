<?php

use App\Actions\Auth\AuthenticateGameJoltUser;
use App\Models\GamejoltAccount;
use App\Models\User;
use Harrk\GameJoltApi\Callers\Users;
use Harrk\GameJoltApi\GamejoltApi;
use Mockery\MockInterface;

it('stores the authenticated gamejolt token for later sync jobs', function () {
    $user = User::factory()->create();
    $account = GamejoltAccount::factory()->create([
        'user_id' => $user->id,
        'username' => 'trainer',
        'token' => 'stale-token',
    ]);

    $users = Mockery::mock(Users::class);
    $users->shouldReceive('auth')
        ->once()
        ->with('trainer', 'fresh-token')
        ->andReturn([
            'response' => [
                'success' => 'true',
            ],
        ]);

    $api = Mockery::mock(GamejoltApi::class, function (MockInterface $mock) use ($users) {
        $mock->shouldReceive('users')->once()->andReturn($users);
    });

    $result = (new AuthenticateGameJoltUser($api))('trainer', 'fresh-token');

    expect($result)->toBeInstanceOf(User::class)
        ->and($result->is($user))->toBeTrue()
        ->and($account->fresh()->token)->toBe('fresh-token')
        ->and($account->fresh()->verified_at)->not->toBeNull();
});

it('maps unknown gamejolt credentials to a friendly error', function () {
    $users = Mockery::mock(Users::class);
    $users->shouldReceive('auth')
        ->once()
        ->andReturn([
            'response' => [
                'success' => 'false',
                'message' => 'No such user with the credentials passed in could be found.',
            ],
        ]);

    $api = Mockery::mock(GamejoltApi::class, function (MockInterface $mock) use ($users) {
        $mock->shouldReceive('users')->once()->andReturn($users);
    });

    $result = (new AuthenticateGameJoltUser($api))('trainer', 'bad-token');

    expect($result)->toBe('Username and/or token is wrong.');
});
