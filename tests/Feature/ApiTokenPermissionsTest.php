<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

test('api token permissions can be updated', function () {
    $user = User::factory()->create();

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    $this->actingAs($user)
        ->put(route('api-tokens.update', ['token' => $token->id]), [
            'permissions' => [
                'delete',
                'missing-permission',
            ],
        ])
        ->assertRedirect();

    expect($user->fresh()->tokens->first())
        ->can('delete')->toBeTrue()
        ->can('read')->toBeFalse()
        ->can('missing-permission')->toBeFalse();
})->skip(fn () => ! Features::hasApiFeatures(), 'API support is not enabled.');
