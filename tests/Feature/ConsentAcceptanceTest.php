<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('authenticated users without the required consent receive terms acceptance props', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('termsAcceptance.required', true)
            ->where('termsAcceptance.key', config('app.required_consent'))
            ->has('termsAcceptance.text'));
});

test('authenticated users with the required consent do not receive terms acceptance props', function () {
    $user = User::factory()->create();
    $consent = config('app.required_consent');

    $user->giveConsentTo($consent, [
        'text' => config('app.consents.'.$consent),
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('termsAcceptance', null));
});

test('users can accept the required terms consent', function () {
    $user = User::factory()->create();
    $consent = config('app.required_consent');

    expect($user->hasGivenConsent($consent))->toBeFalse();

    $this->actingAs($user)
        ->post(route('profile.consents.accept-required'))
        ->assertRedirect();

    expect($user->fresh()->hasGivenConsent($consent))->toBeTrue();
});

test('required consent cannot be revoked from the profile consents endpoint', function () {
    $user = User::factory()->create();
    $consent = config('app.required_consent');

    $user->giveConsentTo($consent, [
        'text' => config('app.consents.'.$consent),
    ]);

    $this->actingAs($user)
        ->patch(route('profile.consents.update'), [
            'consent' => $consent,
        ])
        ->assertRedirect();

    expect($user->fresh()->hasGivenConsent($consent))->toBeTrue();
});

test('optional consents can still be toggled', function () {
    $user = User::factory()->create();
    $consent = 'email.newsletter';

    expect($user->hasGivenConsent($consent))->toBeFalse();

    $this->actingAs($user)
        ->patch(route('profile.consents.update'), [
            'consent' => $consent,
        ])
        ->assertRedirect();

    expect($user->fresh()->hasGivenConsent($consent))->toBeTrue();

    $this->actingAs($user)
        ->patch(route('profile.consents.update'), [
            'consent' => $consent,
        ])
        ->assertRedirect();

    expect($user->fresh()->hasGivenConsent($consent))->toBeFalse();
});
