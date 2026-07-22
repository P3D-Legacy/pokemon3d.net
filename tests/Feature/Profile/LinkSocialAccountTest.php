<?php

use App\Actions\Profile\LinkGamejoltAccount;
use App\Models\GamejoltAccount;
use App\Models\User;
use Harrk\GameJoltApi\Callers\Users;
use Harrk\GameJoltApi\GamejoltApi;
use Illuminate\Support\Facades\Artisan;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;

beforeEach(function () {
    config([
        'services.gamejolt.game_id' => '12345',
        'services.gamejolt.private_key' => 'test-private-key',
        'services.discord.client_id' => 'discord-id',
        'services.discord.client_secret' => 'discord-secret',
    ]);
});

test('profile page exposes connect actions for enabled social providers', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->where('socialAccounts.discord.enabled', true)
            ->where('socialAccounts.discord.connected', false)
            ->where('socialAccounts.discord.connect_url', route('discord.login'))
            ->where('socialAccounts.gamejolt.enabled', true)
            ->where('socialAccounts.gamejolt.connected', false)
            ->where('socialAccounts.gamejolt.uses_credentials', true));
});

test('authenticated users can disconnect a linked social account', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create([
        'user_id' => $user->id,
        'username' => 'trainer',
    ]);

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->delete(route('profile.social.destroy'), [
            'provider' => 'gamejolt',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('status');

    expect($user->fresh()->gamejolt)->toBeNull();
});

test('authenticated users can link a gamejolt account from the profile page', function () {
    Artisan::shouldReceive('call')
        ->once()
        ->with('p3d:skinuserupdate')
        ->andReturn(0);

    $user = User::factory()->create();

    $users = Mockery::mock(Users::class);
    $users->shouldReceive('auth')
        ->once()
        ->with('trainer', 'token123')
        ->andReturn([
            'response' => [
                'success' => 'true',
            ],
        ]);
    $users->shouldReceive('fetch')
        ->once()
        ->with('trainer', 'token123')
        ->andReturn([
            'response' => [
                'users' => [
                    ['id' => 424242],
                ],
            ],
        ]);

    $api = Mockery::mock(GamejoltApi::class, function (MockInterface $mock) use ($users) {
        $mock->shouldReceive('users')->twice()->andReturn($users);
    });

    $this->instance(LinkGamejoltAccount::class, new LinkGamejoltAccount($api));

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->post(route('profile.social.gamejolt.store'), [
            'username' => 'trainer',
            'token' => 'token123',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('status');

    $account = $user->fresh()->gamejolt;

    expect($account)->not->toBeNull()
        ->and($account->username)->toBe('trainer')
        ->and($account->token)->toBe('token123')
        ->and((int) $account->id)->toBe(424242);
});

test('linking rejects a gamejolt username already used by another user', function () {
    $owner = User::factory()->create();
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $owner->id,
        'username' => 'trainer',
    ]);

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->post(route('profile.social.gamejolt.store'), [
            'username' => 'trainer',
            'token' => 'token123',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors(['username']);

    expect($user->fresh()->gamejolt)->toBeNull();
});

test('linking rejects a gamejolt id already used by another user', function () {
    Artisan::shouldReceive('call')->never();

    $owner = User::factory()->create();
    $user = User::factory()->create();

    GamejoltAccount::factory()->create([
        'user_id' => $owner->id,
        'id' => 424242,
        'username' => 'owner-gj',
    ]);

    $users = Mockery::mock(Users::class);
    $users->shouldReceive('auth')
        ->once()
        ->andReturn([
            'response' => [
                'success' => 'true',
            ],
        ]);
    $users->shouldReceive('fetch')
        ->once()
        ->andReturn([
            'response' => [
                'users' => [
                    ['id' => 424242],
                ],
            ],
        ]);

    $api = Mockery::mock(GamejoltApi::class, function (MockInterface $mock) use ($users) {
        $mock->shouldReceive('users')->twice()->andReturn($users);
    });

    $this->instance(LinkGamejoltAccount::class, new LinkGamejoltAccount($api));

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->post(route('profile.social.gamejolt.store'), [
            'username' => 'trainer',
            'token' => 'token123',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors(['error']);

    expect($user->fresh()->gamejolt)->toBeNull();
});

test('guests cannot link social accounts', function () {
    $this->post(route('profile.social.gamejolt.store'), [
        'username' => 'trainer',
        'token' => 'token123',
    ])->assertRedirect(route('login'));
});
