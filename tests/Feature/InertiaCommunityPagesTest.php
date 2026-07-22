<?php

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
    User::factory()->create([
        'username' => 'listedtrainer',
        'location' => 'Pallet Town',
        'last_active_at' => now()->subHour(),
    ]);

    $this->get(route('member.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/index')
            ->has('members.data', 1)
            ->where('members.data.0.username', 'listedtrainer')
            ->where('members.data.0.location', 'Pallet Town')
            ->has('members.data.0.joined')
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
    ]);

    $this->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.username', 'trainerone')
            ->has('member.gameSave'));
});

test('review page is rendered with inertia', function () {
    $this->get(route('review'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('review/index')
            ->has('reviews')
            ->has('gameVersions'));
});

test('servers index is rendered with inertia', function () {
    $this->get(route('server.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('servers/index')
            ->has('servers')
            ->has('myServers'));
});

test('authenticated users can create a server', function () {
    $user = User::factory()->create();

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
