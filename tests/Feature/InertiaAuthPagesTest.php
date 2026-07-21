<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Fortify\Features;

test('login screen is rendered with inertia', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/login')
            ->has('canResetPassword')
            ->has('socialLogin'));
});

test('register screen is rendered with inertia', function () {
    $this->get(route('register'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('auth/register')
            ->has('hasTermsAndPrivacyPolicyFeature'));
})->skip(fn () => ! Features::enabled(Features::registration()), 'Registration support is not enabled.');

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create([
        'password' => bcrypt('SuperSecretPassword123!'),
    ]);

    $response = $this->post(route('login'), [
        'username' => $user->email,
        'password' => 'SuperSecretPassword123!',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(AppServiceProvider::HOME);
});

test('forgot password screen is rendered with inertia', function () {
    $this->get(route('password.request'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('auth/forgot-password'));
})->skip(fn () => ! Features::enabled(Features::resetPasswords()), 'Password updates are not enabled.');

test('confirm password screen is rendered with inertia', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('password.confirm'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('auth/confirm-password'));
});

test('email verification prompt is rendered with inertia', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('auth/verify-email'));
})->skip(fn () => ! Features::enabled(Features::emailVerification()), 'Email verification is not enabled.');
