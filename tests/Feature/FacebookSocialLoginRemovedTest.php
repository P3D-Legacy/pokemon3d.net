<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('facebook login routes are no longer registered', function () {
    $this->get('/login/facebook')->assertNotFound();
    $this->get('/login/facebook/callback')->assertNotFound();
});

test('shared social login props do not include facebook', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->missing('socialLogin.facebook')
            ->has('socialLogin.discord')
            ->has('socialLogin.twitch'));
});

test('profile social accounts do not include facebook', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->missing('socialAccounts.facebook')
            ->has('socialAccounts.discord')
            ->has('socialAccounts.twitch')
            ->has('socialAccounts.gamejolt'));
});

test('facebook cannot be disconnected as a social provider', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('profile.social.destroy'), [
            'provider' => 'facebook',
        ])
        ->assertSessionHasErrors('provider');
});
