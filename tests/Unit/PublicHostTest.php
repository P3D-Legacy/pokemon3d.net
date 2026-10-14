<?php

use App\Support\PublicHost;

test('public ip addresses can be connected to directly', function (string $ip) {
    expect((new PublicHost)->connectAddress($ip))->toBe($ip);
})->with([
    '1.1.1.1',
    '8.8.8.8',
]);

test('internal and non public addresses are rejected', function (string $host) {
    expect((new PublicHost)->connectAddress($host))->toBeNull();
})->with([
    '127.0.0.1',
    '10.0.0.5',
    '192.168.1.1',
    '172.16.0.1',
    '169.254.169.254',
    '0.0.0.0',
    '100.64.0.1',
    'localhost',
    '::1',
    'fe80::1',
    '[::1]',
    '::ffff:127.0.0.1',
    '::ffff:169.254.169.254',
    'not a host',
    'http://1.1.1.1',
    '',
]);

test('a hostname is accepted only when every resolved address is public', function () {
    $hosts = new PublicHost(fn (string $host): array => match ($host) {
        'play.example' => ['1.1.1.1'],
        'mixed.example' => ['1.1.1.1', '10.0.0.5'],
        'private.example' => ['127.0.0.1'],
        default => [],
    });

    expect($hosts->connectAddress('play.example'))->toBe('1.1.1.1')
        ->and($hosts->connectAddress('mixed.example'))->toBeNull()
        ->and($hosts->connectAddress('private.example'))->toBeNull()
        ->and($hosts->connectAddress('missing.example'))->toBeNull();
});
