<?php

use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\Server;
use App\Models\User;
use App\Rules\IPHostnameARecord;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Jetstream\Jetstream;

test('terms page is rendered with inertia', function () {
    $this->get(route('terms.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('terms')
            ->where('title', 'Terms and Conditions')
            ->where('category', 'Legal')
            ->has('updatedAt')
            ->has('readTime')
            ->missing('html'));
})->skip(fn () => ! Jetstream::hasTermsAndPrivacyPolicyFeature(), 'Terms feature disabled.');

test('privacy policy page is rendered with inertia', function () {
    $this->get(route('policy.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('policy')
            ->where('title', 'Privacy Policy')
            ->where('category', 'Legal')
            ->has('updatedAt')
            ->has('readTime')
            ->missing('html'));
})->skip(fn () => ! Jetstream::hasTermsAndPrivacyPolicyFeature(), 'Privacy feature disabled.');

test('members index is rendered with inertia', function () {
    $user = User::factory()->create([
        'username' => 'listedtrainer',
        'location' => 'Pallet Town',
        'last_active_at' => now()->subHour(),
        'created_at' => now()->subYears(2),
    ]);

    $this->get(route('member.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/index')
            ->has('members.data', 1)
            ->where('members.data.0.username', 'listedtrainer')
            ->where('members.data.0.location', 'Pallet Town')
            ->has('members.data.0.joined')
            ->where('members.data.0.joined_for_humans', $user->created_at->diffForHumans())
            ->has('members.data.0.last_online')
            ->where('members.data.0.has_game_save', false)
            ->where('members.data.0.has_gamejolt', false)
            ->has('members.data.0.profile_photo_url')
            ->has('members.data.0.url')
            ->has('members.links')
            ->where('members.per_page', 24));
});

test('members show is rendered with inertia', function () {
    $user = User::factory()->create([
        'username' => 'trainerone',
        'created_at' => now()->subYears(3),
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.username', 'trainerone')
            ->where('member.about.joined_for_humans', $user->created_at->diffForHumans())
            ->has('member.about.joined')
            ->has('member.gameSave'));
});

test('review page is rendered with inertia', function () {
    $this->get(route('review'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('review/index')
            ->has('reviews')
            ->has('averageRating')
            ->has('numberOfReviews')
            ->has('gameVersions')
            ->has('canCreate'));
});

test('servers index is rendered with inertia', function () {
    $this->get(route('server.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('servers/index')
            ->has('servers')
            ->has('myServers')
            ->where('canCreate', false)
            ->where('createRequirements', null));
});

test('servers index shows create requirements when authenticated without gamejolt or save', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('server.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('servers/index')
            ->where('canCreate', false)
            ->where('createRequirements.has_gamejolt', false)
            ->where('createRequirements.has_game_save', false));
});

test('servers index allows create when user has gamejolt and synced save', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    GameSave::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('server.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('servers/index')
            ->where('canCreate', true)
            ->where('createRequirements.has_gamejolt', true)
            ->where('createRequirements.has_game_save', true));
});

test('authenticated users without gamejolt cannot create a server', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('server.create'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('server.store'), [
            'name' => 'Test Server',
            'host' => '127.0.0.1',
            'port' => 40000,
            'description' => 'A test server',
        ])
        ->assertForbidden();

    expect(Server::query()->where('name', 'Test Server')->exists())->toBeFalse();
});

test('authenticated users with gamejolt but no save cannot create a server', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('server.create'))
        ->assertForbidden();

    $this->actingAs($user)
        ->post(route('server.store'), [
            'name' => 'Test Server',
            'host' => '127.0.0.1',
            'port' => 40000,
            'description' => 'A test server',
        ])
        ->assertForbidden();

    expect(Server::query()->where('name', 'Test Server')->exists())->toBeFalse();
});

test('authenticated users can create a server', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    GameSave::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('server.store'), [
            'name' => 'Test Server',
            'host' => '127.0.0.1',
            'port' => 40000,
            'description' => 'A test server',
        ])
        ->assertRedirect(route('server.index'));

    expect(Server::query()->where('name', 'Test Server')->exists())->toBeTrue();
})->skip(fn () => ! class_exists(IPHostnameARecord::class), 'Server validation unavailable.');

test('server owner can edit their server page', function () {
    $user = User::factory()->create();
    $server = Server::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->actingAs($user)
        ->get(route('server.edit', $server))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('servers/edit')
            ->where('server.uuid', $server->uuid));
});

test('non owners cannot edit another users server', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $server = Server::factory()->create([
        'user_id' => $owner->id,
    ]);

    $this->actingAs($other)
        ->get(route('server.edit', $server))
        ->assertForbidden();
});
