<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('user accounts can be deleted', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->delete(route('current-user.destroy'), [
            'password' => 'password',
        ])
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
})->skip(fn () => ! Features::hasAccountDeletionFeatures(), 'Account deletion is not enabled.');

test('correct password must be provided before account can be deleted', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->delete(route('current-user.destroy'), [
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors('password');

    expect($user->fresh())->not->toBeNull();
})->skip(fn () => ! Features::hasAccountDeletionFeatures(), 'Account deletion is not enabled.');
