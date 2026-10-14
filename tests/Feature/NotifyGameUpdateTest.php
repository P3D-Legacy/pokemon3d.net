<?php

use App\Models\GameVersion;
use App\Models\User;
use App\Notifications\NewGameUpdateNotification;
use App\Notifications\Post\CommentLikeNotification;
use App\Notifications\Post\CommentNotification;
use App\Notifications\Post\CommentReplyNotification;
use App\Notifications\Resource\LikeNotification as ResourceLikeNotification;
use App\Notifications\Resource\UpdateNotification;
use App\Notifications\Save\GameSaveFixRequestAssignedNotification;
use App\Notifications\Save\GameSaveFixRequestStaffStatusNotification;
use App\Notifications\Save\GameSaveFixRequestStatusNotification;
use App\Notifications\Skin\LikeNotification as SkinLikeNotification;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

test('game update notifications are queued for newsletter subscribers', function () {
    Notification::fake();

    $subscriber = User::factory()->create();
    $subscriber->giveConsentTo('email.newsletter');
    $other = User::factory()->create();

    GameVersion::factory()->create([
        'version' => '0.1.0',
        'release_date' => Carbon::yesterday()->subDay(),
    ]);
    GameVersion::factory()->create([
        'version' => '0.2.0',
        'release_date' => Carbon::yesterday(),
    ]);

    $this->artisan('notify:gameupdate')
        ->assertSuccessful()
        ->expectsOutputToContain('New update found: 0.2.0');

    Notification::assertSentTo($subscriber, NewGameUpdateNotification::class);
    Notification::assertNotSentTo($other, NewGameUpdateNotification::class);
});

test('game update command skips releases that were not published yesterday', function () {
    Notification::fake();

    $subscriber = User::factory()->create();
    $subscriber->giveConsentTo('email.newsletter');

    GameVersion::factory()->create([
        'release_date' => now()->subDays(3),
    ]);

    $this->artisan('notify:gameupdate')
        ->assertSuccessful()
        ->expectsOutputToContain('No new update found');

    Notification::assertNothingSent();
});

test('mail notifications are pushed onto the queue', function () {
    Queue::fake();

    $user = User::factory()->create();
    $version = GameVersion::factory()->create();

    $user->notify(new NewGameUpdateNotification($version));

    Queue::assertPushed(SendQueuedNotifications::class);

    $notifications = [
        NewGameUpdateNotification::class,
        CommentNotification::class,
        CommentReplyNotification::class,
        CommentLikeNotification::class,
        ResourceLikeNotification::class,
        UpdateNotification::class,
        SkinLikeNotification::class,
        GameSaveFixRequestAssignedNotification::class,
        GameSaveFixRequestStaffStatusNotification::class,
        GameSaveFixRequestStatusNotification::class,
    ];

    foreach ($notifications as $notification) {
        expect(class_implements($notification))->toContain(ShouldQueue::class);
    }
});
