<?php

use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('current profile information is available', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->where('profile.name', $user->name)
            ->where('profile.email', $user->email));
});

test('profile information can be updated', function () {
    $user = User::factory()->create([
        'username' => 'trainerone',
    ]);

    $this->actingAs($user)
        ->put(route('user-profile-information.update'), [
            'name' => 'Test Name',
            'username' => 'trainerone',
            'email' => 'test@example.com',
            'gender' => 0,
            'location' => 'Test Location',
            'about' => 'Test About',
            'birthdate' => '01-01-2000',
        ])
        ->assertRedirect();

    expect($user->fresh())
        ->name->toEqual('Test Name')
        ->email->toEqual('test@example.com');
});
