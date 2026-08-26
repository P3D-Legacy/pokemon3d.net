<?php

use App\Providers\AppServiceProvider;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('auth/register'));
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration screen cannot be rendered if support is disabled', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
})->skip(function () {
    return Features::enabled(Features::registration());
}, 'Registration support is enabled.');

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'username' => 'testuser',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(AppServiceProvider::HOME);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');
