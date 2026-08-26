<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'SuperSecretPassword123!',
            'password_confirmation' => 'SuperSecretPassword123!',
        ])
        ->assertRedirect();

    expect(Hash::check('SuperSecretPassword123!', $user->fresh()->password))->toBeTrue();
});

test('current password must be correct', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->put(route('user-password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors('current_password', errorBag: 'updatePassword');

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new passwords must match', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors('password', errorBag: 'updatePassword');

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});
