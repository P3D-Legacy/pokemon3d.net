<?php

use App\Actions\Save\ManageGameSaveFixRequest;
use App\Enums\GameSaveFixRequestStatus;
use App\Filament\Resources\GameSaveFixRequestResource\Pages\ListGameSaveFixRequests;
use App\Filament\Resources\GameSaveFixRequestResource\Pages\ViewGameSaveFixRequest;
use App\Jobs\SyncGameSaveForUser;
use App\Models\GamejoltAccount;
use App\Models\GameSaveFixRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
    config(['game-save.discord_webhook' => 'https://discord.test/webhook']);
    Http::fake([
        'discord.test/*' => Http::response(['ok' => true]),
    ]);
});

test('moderator can list save fix requests', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    GameSaveFixRequest::factory()->create();

    $this->actingAs($user);

    Livewire::test(ListGameSaveFixRequests::class)
        ->assertSuccessful();
});

test('regular user cannot list save fix requests', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    Livewire::test(ListGameSaveFixRequests::class)
        ->assertForbidden();
});

test('moderator can claim and resolve a save fix request', function () {
    Notification::fake();
    Queue::fake();

    $staff = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $staff->assignRole('moderator');

    $requester = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $requester->id]);
    $request = GameSaveFixRequest::factory()->create(['user_id' => $requester->id]);

    $this->actingAs($staff);

    Livewire::test(ViewGameSaveFixRequest::class, [
        'record' => $request->getRouteKey(),
    ])
        ->assertSuccessful()
        ->callAction('claim')
        ->assertNotified();

    expect($request->refresh()->status)->toBe(GameSaveFixRequestStatus::Claimed)
        ->and($request->assignee_id)->toBe($staff->id);

    Queue::assertPushed(SyncGameSaveForUser::class);

    Livewire::test(ViewGameSaveFixRequest::class, [
        'record' => $request->getRouteKey(),
    ])
        ->callAction('resolve')
        ->assertNotified();

    expect($request->refresh()->status)->toBe(GameSaveFixRequestStatus::Resolved)
        ->and($request->resolved_at)->not->toBeNull();
});

test('manage action resolves claimed requests', function () {
    Notification::fake();

    $staff = User::factory()->create(['email_verified_at' => now()]);
    $staff->assignRole('moderator');
    $request = GameSaveFixRequest::factory()->claimed($staff)->create();

    app(ManageGameSaveFixRequest::class)->resolve($request);

    expect($request->refresh()->status)->toBe(GameSaveFixRequestStatus::Resolved);
});
