<?php

use App\Models\Server;

test('soft-deletes unofficial servers with last_online_at older than 7 days', function () {
    $stale = Server::factory()->create([
        'official' => false,
        'last_online_at' => now()->subDays(8),
    ]);

    $this->artisan('server:prune-unresponsive')
        ->assertSuccessful();

    $this->assertSoftDeleted($stale);
});

test('soft-deletes never-online unofficial servers older than 7 days', function () {
    $neverOnline = Server::factory()->create([
        'official' => false,
        'last_online_at' => null,
        'created_at' => now()->subDays(8),
        'updated_at' => now()->subDays(8),
    ]);

    $this->artisan('server:prune-unresponsive')
        ->assertSuccessful();

    $this->assertSoftDeleted($neverOnline);
});

test('leaves recent unofficial servers alone', function () {
    $recent = Server::factory()->create([
        'official' => false,
        'last_online_at' => now()->subDays(2),
    ]);

    $this->artisan('server:prune-unresponsive')
        ->assertSuccessful();

    expect($recent->fresh())->not->toBeNull()
        ->and($recent->fresh()->trashed())->toBeFalse();
});

test('leaves official servers alone even when stale', function () {
    $official = Server::factory()->create([
        'official' => true,
        'last_online_at' => now()->subDays(30),
    ]);

    $this->artisan('server:prune-unresponsive')
        ->assertSuccessful();

    expect($official->fresh())->not->toBeNull()
        ->and($official->fresh()->trashed())->toBeFalse();
});
