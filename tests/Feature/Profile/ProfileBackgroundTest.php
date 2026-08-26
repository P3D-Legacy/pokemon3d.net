<?php

use App\Jobs\SyncGameSaveForUser;
use App\Models\GamejoltAccount;
use App\Models\GameSave;
use App\Models\User;
use App\Services\GameJoltDataStoreGateway;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery\MockInterface;

beforeEach(function () {
    config([
        'services.gamejolt.game_id' => '12345',
        'services.gamejolt.private_key' => 'test-private-key',
    ]);
});

it('syncs the in-game emblem selection without overwriting a website override', function () {
    $user = User::factory()->create([
        'profile_background' => 'champion',
        'gamejolt_emblem' => null,
    ]);
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->mock(GameJoltDataStoreGateway::class, function (MockInterface $mock) {
        $mock->shouldReceive('fetch')
            ->andReturnUsing(function (string $key) {
                if (str_ends_with($key, '|player')) {
                    return [
                        'response' => [
                            'success' => 'true',
                            'data' => "Name|Ash\r\n",
                        ],
                    ];
                }

                if (str_ends_with($key, '|emblem')) {
                    return [
                        'response' => [
                            'success' => 'true',
                            'data' => 'trainer',
                        ],
                    ];
                }

                return [
                    'response' => [
                        'success' => 'false',
                        'message' => 'No item with that key could be found.',
                    ],
                ];
            });
    });

    SyncGameSaveForUser::dispatchSync($user->fresh());

    $user->refresh();

    expect($user->gamejolt_emblem)->toBe('trainer')
        ->and($user->profile_background)->toBe('champion')
        ->and(GameSave::where('user_id', $user->id)->exists())->toBeTrue();
});

it('allows setting an unlocked profile background override', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->put(route('profile.background.update'), [
            'profile_background' => 'trainer',
        ])
        ->assertRedirect(route('profile.show'));

    expect($user->fresh()->profile_background)->toBe('trainer');
});

it('rejects locked profile backgrounds', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->put(route('profile.background.update'), [
            'profile_background' => 'champion',
        ])
        ->assertRedirect(route('profile.show'))
        ->assertSessionHasErrors('profile_background');

    expect($user->fresh()->profile_background)->toBeNull();
});

it('clears the override when null is submitted', function () {
    $user = User::factory()->create([
        'profile_background' => 'trainer',
        'gamejolt_emblem' => 'eevee',
    ]);
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->from(route('profile.show'))
        ->put(route('profile.background.update'), [
            'profile_background' => null,
        ])
        ->assertRedirect(route('profile.show'));

    expect($user->fresh()->profile_background)->toBeNull();
});

it('exposes profile background options on the edit page', function () {
    $user = User::factory()->create([
        'gamejolt_emblem' => 'trainer',
    ]);
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('profile/edit')
            ->where('profileBackground.effective', 'trainer')
            ->where('profileBackground.requires_gamejolt', false)
            ->has('profileBackground.options')
            ->where('profileBackground.options.0.slug', fn ($slug) => is_string($slug)));
});

it('includes cover_image on the public member profile', function () {
    $user = User::factory()->create([
        'username' => 'emblemuser',
        'gamejolt_emblem' => 'trainer',
    ]);

    $this->actingAs($user)
        ->get(route('member.show', $user->username))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('members/show')
            ->where('member.cover_image', fn ($url) => is_string($url) && str_contains($url, '/img/emblems/trainer.png')));
});
