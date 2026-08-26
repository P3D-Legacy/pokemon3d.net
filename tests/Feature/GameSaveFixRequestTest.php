<?php

use App\Actions\Save\ManageGameSaveFixRequest;
use App\Enums\GameSaveFixRequestStatus;
use App\Models\GamejoltAccount;
use App\Models\GameSaveFixRequest;
use App\Models\User;
use App\Notifications\Save\GameSaveFixRequestAssignedNotification;
use App\Notifications\Save\GameSaveFixRequestStatusNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Activitylog\Models\Activity;

beforeEach(function (): void {
    config(['game-save.discord_webhook' => 'https://discord.test/webhook']);
    Http::fake([
        'discord.test/*' => Http::response(['ok' => true]),
    ]);
});

test('guests are redirected from save fix requests', function () {
    $this->get(route('save.fix-requests.index'))
        ->assertRedirect(route('login'));
});

test('authenticated user without gamejolt is redirected from save fix requests', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('save.fix-requests.index'))
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('flash.bannerStyle', 'warning');
});

test('user can create a save fix request with consent', function () {
    Notification::fake();

    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('save.fix-requests.store'), [
            'description' => 'I am stuck in Oak\'s lab and cannot leave.',
            'consent_accepted' => '1',
            'notify_database' => '1',
            'notify_mail' => '1',
        ])
        ->assertRedirect();

    $request = GameSaveFixRequest::query()->first();

    expect($request)->not->toBeNull()
        ->and($request->user_id)->toBe($user->id)
        ->and($request->status)->toBe(GameSaveFixRequestStatus::Open)
        ->and($request->consent_text)->toBe(config('game-save.fix_request_consent_text'))
        ->and($request->consent_accepted_at)->not->toBeNull()
        ->and($request->notify_database)->toBeTrue()
        ->and($request->notify_mail)->toBeTrue();

    Http::assertSent(fn ($httpRequest) => $httpRequest->url() === 'https://discord.test/webhook');

    expect(Activity::query()
        ->where('subject_type', GameSaveFixRequest::class)
        ->where('subject_id', $request->uuid)
        ->exists())->toBeTrue();
});

test('user cannot create a request without consent', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('save.fix-requests.store'), [
            'description' => 'I am stuck in Oak\'s lab and cannot leave.',
            'notify_database' => '1',
            'notify_mail' => '1',
        ])
        ->assertSessionHasErrors('consent_accepted');

    expect(GameSaveFixRequest::query()->count())->toBe(0);
});

test('user cannot open a second active save fix request', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    GameSaveFixRequest::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('save.fix-requests.store'), [
            'description' => 'Another issue with my save file today.',
            'consent_accepted' => '1',
            'notify_database' => '1',
            'notify_mail' => '1',
        ])
        ->assertSessionHasErrors('description');

    expect(GameSaveFixRequest::query()->count())->toBe(1);
});

test('user can cancel their open request', function () {
    Notification::fake();

    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    $request = GameSaveFixRequest::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->post(route('save.fix-requests.cancel', $request))
        ->assertRedirect(route('save.fix-requests.show', $request));

    expect($request->refresh()->status)->toBe(GameSaveFixRequestStatus::Cancelled);

    Notification::assertNotSentTo($user, GameSaveFixRequestStatusNotification::class);
});

test('user can update notification preferences', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    $request = GameSaveFixRequest::factory()->create([
        'user_id' => $user->id,
        'notify_database' => true,
        'notify_mail' => true,
    ]);

    $this->actingAs($user)
        ->patch(route('save.fix-requests.notifications', $request), [
            'notify_database' => '1',
        ])
        ->assertRedirect();

    expect($request->refresh()->notify_database)->toBeTrue()
        ->and($request->notify_mail)->toBeFalse();
});

test('user can view their request list and detail', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    $request = GameSaveFixRequest::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('save.fix-requests.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('save/fix-requests/index')
            ->has('requests.data', 1));

    $this->actingAs($user)
        ->get(route('save.fix-requests.show', $request))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('save/fix-requests/show')
            ->where('fixRequest.uuid', $request->uuid));
});

test('requester is notified when staff claims the request', function () {
    Notification::fake();
    seedPermissions();

    $user = User::factory()->create();
    $user->giveConsentTo('email.notifications');
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
    $request = GameSaveFixRequest::factory()->create(['user_id' => $user->id]);

    $staff = User::factory()->create(['email_verified_at' => now()]);
    $staff->assignRole('moderator');

    app(ManageGameSaveFixRequest::class)->claim($request, $staff);

    Notification::assertSentTo($user, GameSaveFixRequestStatusNotification::class);
    Notification::assertSentTo($staff, GameSaveFixRequestAssignedNotification::class);
    expect($request->refresh()->status)->toBe(GameSaveFixRequestStatus::Claimed)
        ->and($request->assignee_id)->toBe($staff->id);
});
