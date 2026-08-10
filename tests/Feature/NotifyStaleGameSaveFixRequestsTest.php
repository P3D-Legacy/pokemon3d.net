<?php

use App\Enums\GameSaveFixRequestStatus;
use App\Models\GameSaveFixRequest;
use Illuminate\Support\Facades\Http;

beforeEach(function (): void {
    config(['game-save.discord_webhook' => 'https://discord.test/webhook']);
    Http::fake([
        'discord.test/*' => Http::response(['ok' => true]),
    ]);
});

test('stale command notifies discord for idle open requests', function () {
    $stale = GameSaveFixRequest::factory()->create([
        'status' => GameSaveFixRequestStatus::Open,
    ]);
    $fresh = GameSaveFixRequest::factory()->create([
        'status' => GameSaveFixRequestStatus::Open,
    ]);

    GameSaveFixRequest::withoutTimestamps(function () use ($stale): void {
        $stale->forceFill([
            'created_at' => now()->subDays(8),
            'updated_at' => now()->subDays(8),
        ])->save();
    });

    $this->artisan('game-save-fix:notify-stale')
        ->assertSuccessful();

    expect($stale->refresh()->stale_notified_at)->not->toBeNull()
        ->and($fresh->refresh()->stale_notified_at)->toBeNull();

    Http::assertSent(fn ($request) => $request->url() === 'https://discord.test/webhook');
});

test('stale command respects previous stale notification window', function () {
    $notifiedAt = now()->subDays(2);

    $request = GameSaveFixRequest::factory()->create([
        'status' => GameSaveFixRequestStatus::Claimed,
        'stale_notified_at' => $notifiedAt,
    ]);

    GameSaveFixRequest::withoutTimestamps(function () use ($request): void {
        $request->forceFill([
            'created_at' => now()->subDays(10),
            'updated_at' => now()->subDays(10),
        ])->save();
    });

    Http::fake();

    $this->artisan('game-save-fix:notify-stale')
        ->assertSuccessful();

    expect($request->refresh()->stale_notified_at->timestamp)->toBe($notifiedAt->timestamp);

    Http::assertNothingSent();
});
