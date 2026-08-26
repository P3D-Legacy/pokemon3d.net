<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('api tokens can be created', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('api-tokens.store'), [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ])
        ->assertRedirect();

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first())
        ->name->toEqual('Test Token')
        ->can('read')->toBeTrue()
        ->can('delete')->toBeFalse();
})->skip(fn () => ! Features::hasApiFeatures(), 'API support is not enabled.');
