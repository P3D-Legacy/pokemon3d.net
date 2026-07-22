<?php

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Jetstream\Features;

test('dashboard is rendered with inertia for authenticated users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('dashboard')
            ->has('copy.welcome')
            ->has('copy.intro')
            ->has('copy.exploreLabel')
            ->has('links.download')
            ->has('links.wiki')
            ->has('links.discord')
            ->where('author.name', 'Nilllzz')
            ->has('author.url'));
});

test('guests are redirected from the dashboard', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});

test('profile page is rendered with inertia', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->where('profile.email', $user->email)
            ->has('sessions')
            ->has('preferences')
            ->has('consents')
            ->has('socialAccounts')
            ->has('features'));
});

test('notifications page is rendered with inertia', function () {
    $user = User::factory()->create();

    DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => [
            'message' => 'Hello trainer',
            'url' => route('dashboard'),
        ],
    ]);

    $this->actingAs($user)
        ->get(route('notifications.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('notifications/index')
            ->has('notifications.data', 1)
            ->where('notifications.data.0.message', 'Hello trainer'));
});

test('opening a notification rejects external redirect targets', function () {
    $user = User::factory()->create();

    $notification = DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => [
            'message' => 'External link',
            'url' => 'https://evil.example',
        ],
    ]);

    $this->actingAs($user)
        ->post(route('notifications.open', $notification->id))
        ->assertRedirect(route('notifications.index'));
});

test('api tokens page is rendered with inertia', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('api-tokens.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('api-tokens/index')
            ->has('tokens')
            ->has('availablePermissions')
            ->has('defaultPermissions'));
})->skip(fn () => ! Features::hasApiFeatures(), 'API support is not enabled.');
