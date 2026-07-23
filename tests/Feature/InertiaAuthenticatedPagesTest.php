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
            ->where('auth.user.username', $user->username)
            ->has('copy.welcome')
            ->has('copy.intro')
            ->has('copy.exploreLabel')
            ->has('links.download')
            ->has('links.wiki')
            ->has('links.discord')
            ->has('links.resources')
            ->missing('links.forum')
            ->where('copy.resources', 'Resources')
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

test('notifications index returns latest notifications as json', function () {
    $user = User::factory()->create();

    DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => [
            'message' => '<strong>Hello trainer</strong>',
            'url' => route('dashboard'),
        ],
    ]);

    $this->actingAs($user)
        ->getJson(route('notifications.index'))
        ->assertOk()
        ->assertJsonCount(1, 'notifications')
        ->assertJsonPath('notifications.0.message', 'Hello trainer')
        ->assertJsonPath('notifications.0.url', route('dashboard'));
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
        ->from(route('dashboard'))
        ->post(route('notifications.open', $notification->id))
        ->assertRedirect(route('dashboard'));
});

test('opening a notification redirects to same-app absolute urls', function () {
    $user = User::factory()->create();

    $notification = DatabaseNotification::create([
        'id' => (string) Str::uuid(),
        'type' => 'App\\Notifications\\TestNotification',
        'notifiable_type' => User::class,
        'notifiable_id' => $user->id,
        'data' => [
            'message' => 'Go to dashboard',
            'url' => route('dashboard'),
        ],
    ]);

    $this->actingAs($user)
        ->post(route('notifications.open', $notification->id))
        ->assertRedirect(route('dashboard'));

    expect($notification->fresh()->read_at)->not->toBeNull();
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
