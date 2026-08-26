<?php

use App\Models\Server;
use App\Services\ServerPinger;
use Illuminate\Support\Facades\Artisan;
use Mockery\MockInterface;

test('fails when the server uuid does not exist', function () {
    $this->artisan('server:ping', ['uuid' => fake()->uuid()])
        ->assertFailed();
});

test('stores ping data when the server is reachable', function () {
    $this->freezeTime();

    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(42);
    });

    $server = Server::factory()->create([
        'name' => 'Kanto Link',
        'official' => false,
        'active' => false,
        'ping' => null,
        'last_online_at' => now()->subDay(),
    ]);

    $this->artisan('server:ping', ['uuid' => $server->uuid])
        ->assertSuccessful()
        ->expectsOutput('Name: Kanto Link - Ping: 42ms');

    $server->refresh();

    expect($server->ping)->toBe(42)
        ->and((bool) $server->active)->toBeTrue()
        ->and($server->last_check_at?->isSameSecond(now()))->toBeTrue()
        ->and($server->last_online_at?->isSameSecond(now()))->toBeTrue();
});

test('treats a zero millisecond ping as reachable', function () {
    $this->freezeTime();

    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(0);
    });

    $server = Server::factory()->create([
        'official' => false,
        'active' => false,
        'last_online_at' => now()->subDays(2),
    ]);

    $this->artisan('server:ping', ['uuid' => $server->uuid])
        ->assertSuccessful();

    $server->refresh();

    expect($server->ping)->toBe(0)
        ->and((bool) $server->active)->toBeTrue()
        ->and($server->last_online_at?->isSameSecond(now()))->toBeTrue();
});

test('deactivates unofficial servers that have been offline for over 24 hours', function () {
    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(null);
    });

    $server = Server::factory()->create([
        'official' => false,
        'active' => true,
        'last_online_at' => now()->subHours(25),
    ]);

    $this->artisan('server:ping', ['uuid' => $server->uuid])
        ->assertSuccessful();

    expect((bool) $server->fresh()->active)->toBeFalse()
        ->and($server->fresh()->ping)->toBeNull();
});

test('leaves official servers active when they are unreachable', function () {
    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(null);
    });

    $server = Server::factory()->create([
        'official' => true,
        'active' => true,
        'last_online_at' => now()->subDays(30),
    ]);

    $this->artisan('server:ping', ['uuid' => $server->uuid])
        ->assertSuccessful();

    expect((bool) $server->fresh()->active)->toBeTrue();
});

test('skips deactivation when the reactivate option is set', function () {
    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(null);
    });

    $server = Server::factory()->create([
        'official' => false,
        'active' => true,
        'last_online_at' => now()->subDays(2),
    ]);

    $this->artisan('server:ping', [
        'uuid' => $server->uuid,
        '--reactivate' => true,
    ])->assertSuccessful();

    expect((bool) $server->fresh()->active)->toBeTrue();
});

test('uses created_at when last_online_at is missing', function () {
    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->twice()->andReturn(null);
    });

    $stale = Server::factory()->create([
        'official' => false,
        'active' => true,
        'last_online_at' => null,
        'created_at' => now()->subHours(25),
        'updated_at' => now()->subHours(25),
    ]);
    $recent = Server::factory()->create([
        'official' => false,
        'active' => true,
        'last_online_at' => null,
        'created_at' => now()->subHours(2),
        'updated_at' => now()->subHours(2),
    ]);

    $this->artisan('server:ping', ['uuid' => $stale->uuid])->assertSuccessful();
    $this->artisan('server:ping', ['uuid' => $recent->uuid])->assertSuccessful();

    expect((bool) $stale->fresh()->active)->toBeFalse()
        ->and((bool) $recent->fresh()->active)->toBeTrue();
});

test('pings every server when no uuid is given', function () {
    $this->freezeTime();

    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')
            ->twice()
            ->andReturnUsing(fn (string $host): ?int => $host === 'online.example' ? 15 : null);
    });

    $online = Server::factory()->create([
        'host' => 'online.example',
        'official' => false,
        'active' => false,
        'last_online_at' => now()->subHour(),
    ]);
    $offline = Server::factory()->create([
        'host' => 'offline.example',
        'official' => false,
        'active' => true,
        'last_online_at' => now()->subHours(25),
    ]);

    $this->artisan('server:ping')
        ->assertSuccessful()
        ->expectsOutput('Pinged 2 server(s). 1 reachable.');

    $online->refresh();
    $offline->refresh();

    expect($online->ping)->toBe(15)
        ->and((bool) $online->active)->toBeTrue()
        ->and($online->last_check_at?->isSameSecond(now()))->toBeTrue()
        ->and($offline->ping)->toBeNull()
        ->and((bool) $offline->active)->toBeFalse()
        ->and($offline->last_check_at?->isSameSecond(now()))->toBeTrue();
});

test('resolves the server:pingall alias', function () {
    expect(array_key_exists('server:pingall', Artisan::all()))->toBeTrue();

    $this->mock(ServerPinger::class, function (MockInterface $mock) {
        $mock->shouldReceive('ping')->once()->andReturn(10);
    });

    Server::factory()->create([
        'official' => false,
        'active' => true,
    ]);

    $this->artisan('server:pingall')
        ->assertSuccessful()
        ->expectsOutput('Pinged 1 server(s). 1 reachable.');
});
