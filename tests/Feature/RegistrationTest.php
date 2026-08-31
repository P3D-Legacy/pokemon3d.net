<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration screen can be rendered', function () {
    $this->get('/register')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/register')
            ->has('pokemonCaptcha', 3)
            ->has('pokemonCaptcha.0.id')
            ->has('pokemonCaptcha.0.question')
            ->has('pokemonCaptcha.0.options')
            ->missing('pokemonCaptcha.0.answer'));
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
        'pokemon_captcha' => pokemonCaptchaAnswers(),
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(AppServiceProvider::HOME);
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration rejects missing pokemon captcha answers', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'nocaptcha@example.com',
        'username' => 'nocaptcha',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ])->assertSessionHasErrors('pokemon_captcha');

    $this->assertGuest();
    expect(User::query()->where('email', 'nocaptcha@example.com')->exists())->toBeFalse();
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');

test('registration rejects incorrect pokemon captcha answers', function () {
    $wrongAnswers = collect(pokemonCaptchaAnswers())
        ->map(fn (): string => 'not-the-answer')
        ->all();

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'wrongcaptcha@example.com',
        'username' => 'wrongcaptcha',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        'pokemon_captcha' => $wrongAnswers,
    ])->assertSessionHasErrors('pokemon_captcha');

    $this->assertGuest();
    expect(User::query()->where('email', 'wrongcaptcha@example.com')->exists())->toBeFalse();
})->skip(function () {
    return ! Features::enabled(Features::registration());
}, 'Registration support is not enabled.');
