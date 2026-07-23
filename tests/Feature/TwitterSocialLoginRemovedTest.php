<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Inertia\Testing\AssertableInertia as Assert;

test('twitter login routes are no longer registered', function () {
    $this->get('/login/twitter')->assertNotFound();
    $this->get('/login/twitter/callback')->assertNotFound();
});

test('twitter services config is not present', function () {
    expect(config('services.twitter'))->toBeNull();
});

test('shared social login props do not include twitter', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->missing('socialLogin.twitter')
            ->has('socialLogin.discord')
            ->has('socialLogin.twitch'));
});

test('profile social accounts do not include twitter', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->missing('socialAccounts.twitter')
            ->has('socialAccounts.discord')
            ->has('socialAccounts.twitch')
            ->has('socialAccounts.gamejolt'));
});

test('twitter cannot be disconnected as a social provider', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('profile.social.destroy'), [
            'provider' => 'twitter',
        ])
        ->assertSessionHasErrors('provider');
});

test('twitter accounts table has been removed', function () {
    expect(Schema::hasTable('twitter_accounts'))->toBeFalse();
});
