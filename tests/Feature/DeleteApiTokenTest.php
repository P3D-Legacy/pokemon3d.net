<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

test('api tokens can be deleted', function () {
    $user = User::factory()->create();

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    $this->actingAs($user)
        ->delete(route('api-tokens.destroy', ['token' => $token->id]))
        ->assertRedirect();

    expect($user->fresh()->tokens)->toHaveCount(0);
})->skip(fn () => ! Features::hasApiFeatures(), 'API support is not enabled.');
