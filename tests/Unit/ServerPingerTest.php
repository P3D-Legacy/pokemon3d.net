<?php

use App\Services\ServerPinger;
use App\Services\StreamSocketConnector;
use App\Support\PublicHost;
use Tests\TestCase;

uses(TestCase::class);

test('does not open a socket for an internal address', function (string $host) {
    $connector = Mockery::mock(StreamSocketConnector::class);
    $connector->shouldNotReceive('connect');

    $pinger = new ServerPinger(new PublicHost, $connector);

    expect($pinger->ping($host, 80))->toBeNull();
})->with([
    '127.0.0.1',
    '10.0.0.5',
    '169.254.169.254',
    'localhost',
]);

test('does not open a socket when any resolved address is private', function () {
    $connector = Mockery::mock(StreamSocketConnector::class);
    $connector->shouldNotReceive('connect');

    $hosts = new PublicHost(fn (): array => ['1.1.1.1', '10.0.0.5']);
    $pinger = new ServerPinger($hosts, $connector);

    expect($pinger->ping('play.example', 40000))->toBeNull();
});

test('connects to the resolved public address rather than the hostname', function () {
    $connector = Mockery::mock(StreamSocketConnector::class);
    $connector->shouldReceive('connect')
        ->once()
        ->with('1.1.1.1', 40000, 2.0)
        ->andReturn(15);

    $hosts = new PublicHost(fn (string $host): array => $host === 'play.example' ? ['1.1.1.1'] : []);
    $pinger = new ServerPinger($hosts, $connector);

    expect($pinger->ping('play.example', 40000))->toBe(15);
});

test('does not open a socket for a port outside the tcp range', function () {
    $connector = Mockery::mock(StreamSocketConnector::class);
    $connector->shouldNotReceive('connect');

    $pinger = new ServerPinger(new PublicHost, $connector);

    expect($pinger->ping('1.1.1.1', 99999))->toBeNull();
});

test('can be resolved from the container', function () {
    expect(app(ServerPinger::class))->toBeInstanceOf(ServerPinger::class);
});
