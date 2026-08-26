<?php

use App\Actions\Auth\AuthenticateForumUser;
use App\Actions\Auth\AuthenticateGameJoltUser;
use App\Models\User;
use App\Providers\AppServiceProvider;

beforeEach(function () {
    config([
        'services.gamejolt.game_id' => '12345',
        'services.gamejolt.private_key' => 'test-private-key',
        'services.xenforo.api_key' => 'test-api-key',
        'services.xenforo.api_url' => 'https://forum.example.test/api',
        'services.xenforo.base_url' => 'https://forum.example.test',
    ]);
});

test('gamejolt login validates required credentials', function () {
    $this->from(route('login'))
        ->post(route('gamejolt.login'), [])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['username', 'token']);
});

test('forum login validates required credentials', function () {
    $this->from(route('login'))
        ->post(route('forum.login'), [])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['username', 'password']);
});

test('gamejolt login authenticates an associated user', function () {
    $user = User::factory()->create();

    $this->mock(AuthenticateGameJoltUser::class, function ($mock) use ($user) {
        $mock->shouldReceive('__invoke')
            ->once()
            ->with('trainer', 'token123')
            ->andReturn($user);
    });

    $this->post(route('gamejolt.login'), [
        'username' => 'trainer',
        'token' => 'token123',
    ])->assertRedirect(AppServiceProvider::HOME);

    $this->assertAuthenticatedAs($user);
});

test('forum login authenticates an associated user', function () {
    $user = User::factory()->create();

    $this->mock(AuthenticateForumUser::class, function ($mock) use ($user) {
        $mock->shouldReceive('__invoke')
            ->once()
            ->with('trainer', 'secret')
            ->andReturn($user);
    });

    $this->post(route('forum.login'), [
        'username' => 'trainer',
        'password' => 'secret',
    ])->assertRedirect(AppServiceProvider::HOME);

    $this->assertAuthenticatedAs($user);
});

test('gamejolt login returns an error when authentication fails', function () {
    $this->mock(AuthenticateGameJoltUser::class, function ($mock) {
        $mock->shouldReceive('__invoke')
            ->once()
            ->andReturn('Username and/or token is wrong.');
    });

    $this->from(route('login'))
        ->post(route('gamejolt.login'), [
            'username' => 'trainer',
            'token' => 'badtoken',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors(['error']);

    $this->assertGuest();
});

test('authenticated users cannot use credential social login routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('gamejolt.login'), [
            'username' => 'trainer',
            'token' => 'token123',
        ])
        ->assertRedirect(AppServiceProvider::HOME);
});
