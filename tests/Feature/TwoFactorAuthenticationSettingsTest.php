<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('two factor authentication can be enabled', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'))
        ->assertRedirect();

    $user = $user->fresh();

    expect($user->two_factor_secret)->not->toBeNull();
    expect($user->recoveryCodes())->toHaveCount(8);
})->skip(fn () => ! Features::canManageTwoFactorAuthentication(), 'Two factor authentication is not enabled.');

test('recovery codes can be regenerated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'))
        ->assertRedirect();

    $codes = $user->fresh()->recoveryCodes();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(url('/user/two-factor-recovery-codes'))
        ->assertRedirect();

    expect($user->fresh()->recoveryCodes())->toHaveCount(8);
    expect(array_diff($codes, $user->fresh()->recoveryCodes()))->toHaveCount(8);
})->skip(fn () => ! Features::canManageTwoFactorAuthentication(), 'Two factor authentication is not enabled.');

test('two factor authentication can be disabled', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'))
        ->assertRedirect();

    expect($user->fresh()->two_factor_secret)->not->toBeNull();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->delete(route('two-factor.disable'))
        ->assertRedirect();

    expect($user->fresh()->two_factor_secret)->toBeNull();
})->skip(fn () => ! Features::canManageTwoFactorAuthentication(), 'Two factor authentication is not enabled.');
