<?php

use App\Models\User;
use App\Rules\SpamMail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

beforeEach(function () {
    config(['laravel-spammail-checker.default' => env('SPAM_MAIL_CHECKER_DEFAULT_DRIVER', 'local')]);
    app()->forgetInstance('spammailchecker');
});

test('spam mail rule rejects domains from the local spam list', function () {
    expect(Validator::make(
        ['email' => 'user@mailinator.com'],
        ['email' => [new SpamMail]]
    )->fails())->toBeTrue();
});

test('spam mail rule allows legitimate email domains', function () {
    expect(Validator::make(
        ['email' => 'user@gmail.com'],
        ['email' => [new SpamMail]]
    )->passes())->toBeTrue();
});

test('spam mail rule rejects non-existent domains with the remote driver', function () {
    config(['laravel-spammail-checker.default' => 'remote']);
    app()->forgetInstance('spammailchecker');

    expect(Validator::make(
        ['email' => 'user@this-domain-definitely-does-not-exist-xyz123.invalid'],
        ['email' => [new SpamMail]]
    )->fails())->toBeTrue();
});

test('registration rejects spam email domains', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'spam@mailinator.com',
        'username' => 'spammailreg',
        'password' => 'SuperSecretPassword123!',
        'password_confirmation' => 'SuperSecretPassword123!',
        'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        'pokemon_captcha' => pokemonCaptchaAnswers(),
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
    expect(User::query()->where('email', 'spam@mailinator.com')->exists())->toBeFalse();
})->skip(fn () => ! Features::enabled(Features::registration()), 'Registration support is not enabled.');

test('profile updates reject spam email domains', function () {
    $user = User::factory()->create([
        'username' => 'trainerone',
    ]);

    $this->actingAs($user)
        ->put(route('user-profile-information.update'), [
            'name' => 'Test Name',
            'username' => 'trainerone',
            'email' => 'spam@mailinator.com',
            'gender' => 0,
            'location' => 'Test Location',
            'about' => 'Test About',
            'birthdate' => '01-01-2000',
        ])
        ->assertSessionHasErrors('email', errorBag: 'updateProfileInformation');

    expect($user->fresh()->email)->not->toEqual('spam@mailinator.com');
});
