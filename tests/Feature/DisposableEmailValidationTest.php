<?php

use App\Models\User;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

test('registration rejects disposable email addresses', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'spam@mailinator.com',
        'username' => 'disposablereg',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(User::query()->where('email', 'spam@mailinator.com')->exists())->toBeFalse();
})->skip(fn () => ! Features::enabled(Features::registration()), 'Registration support is not enabled.');

test('registration rejects disposable email subdomains', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'spam@temp.mailinator.com',
        'username' => 'disposablesub',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
})->skip(fn () => ! Features::enabled(Features::registration()), 'Registration support is not enabled.');

test('profile updates reject disposable email addresses', function () {
    $user = User::factory()->create([
        'username' => 'trainerone',
    ]);

    $this->actingAs($user)
        ->put(route('user-profile-information.update'), [
            'name' => 'Test Name',
            'username' => 'trainerone',
            'email' => 'spam@guerrillamail.com',
            'gender' => 0,
            'location' => 'Test Location',
            'about' => 'Test About',
            'birthdate' => '01-01-2000',
        ])
        ->assertSessionHasErrors('email', errorBag: 'updateProfileInformation');

    expect($user->fresh()->email)->not->toEqual('spam@guerrillamail.com');
});
