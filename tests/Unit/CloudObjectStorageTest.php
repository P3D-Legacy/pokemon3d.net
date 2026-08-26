<?php

use App\Support\CloudObjectStorage;
use App\Support\SkinStorage;
use Tests\TestCase;

uses(TestCase::class);

afterEach(function () {
    unset($_SERVER['LARAVEL_CLOUD_DISK_CONFIG']);
});

test('configure does nothing when laravel cloud disk config is missing', function () {
    unset($_SERVER['LARAVEL_CLOUD_DISK_CONFIG']);

    config([
        'filesystems.disks.skin.disk' => 's3',
        'filesystems.disks.resource.disk' => 's3',
        'jetstream.profile_photo_disk' => 's3',
    ]);

    CloudObjectStorage::configure();

    expect(CloudObjectStorage::injectedDiskName())->toBeNull()
        ->and(config('filesystems.disks.skin.disk'))->toBe('s3')
        ->and(config('filesystems.disks.resource.disk'))->toBe('s3')
        ->and(config('jetstream.profile_photo_disk'))->toBe('s3');
});

test('configure retargets scoped disks to the injected laravel cloud disk', function () {
    $_SERVER['LARAVEL_CLOUD_DISK_CONFIG'] = json_encode([
        [
            'disk' => 'cloud',
            'access_key_id' => 'key',
            'access_key_secret' => 'secret',
            'bucket' => 'bucket',
            'url' => 'https://fls.example.laravel.cloud',
            'endpoint' => 'https://example.invalid',
            'is_default' => true,
        ],
    ]);

    config([
        'filesystems.object_public_url' => null,
        'filesystems.disks.s3' => [
            'driver' => 'local',
            'root' => storage_path('app/object'),
            'url' => 'https://pokemon3d.net.test/storage/object',
        ],
        'filesystems.disks.cloud' => [
            'driver' => 's3',
            'key' => 'key',
            'secret' => 'secret',
            'bucket' => 'bucket',
            'url' => 'https://fls.example.laravel.cloud',
            'endpoint' => 'https://example.invalid',
            'region' => 'auto',
        ],
        'filesystems.disks.skin' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'skin',
        ],
        'filesystems.disks.resource' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'resource',
        ],
        'jetstream.profile_photo_disk' => 's3',
    ]);

    CloudObjectStorage::configure();

    expect(config('filesystems.disks.skin.disk'))->toBe('cloud')
        ->and(config('filesystems.disks.resource.disk'))->toBe('cloud')
        ->and(config('jetstream.profile_photo_disk'))->toBe('cloud')
        ->and(SkinStorage::urlLibrary('abc', 1))
        ->toStartWith('https://fls.example.laravel.cloud/skin/abc.png')
        ->and(SkinStorage::urlLibrary('abc', 1))
        ->not->toContain('/storage/object/');
});

test('configure fills an empty injected disk url from the object public url', function () {
    $_SERVER['LARAVEL_CLOUD_DISK_CONFIG'] = json_encode([
        [
            'disk' => 's3',
            'access_key_id' => 'key',
            'access_key_secret' => 'secret',
            'bucket' => 'bucket',
            'url' => '',
            'endpoint' => 'https://example.invalid',
            'is_default' => true,
        ],
    ]);

    config([
        'filesystems.object_public_url' => 'https://fls.example.laravel.cloud',
        'filesystems.disks.s3' => [
            'driver' => 's3',
            'key' => 'key',
            'secret' => 'secret',
            'bucket' => 'bucket',
            'url' => '',
            'endpoint' => 'https://example.invalid',
            'region' => 'auto',
        ],
        'filesystems.disks.skin' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'skin',
        ],
    ]);

    CloudObjectStorage::configure();

    expect(config('filesystems.disks.s3.url'))->toBe('https://fls.example.laravel.cloud')
        ->and(SkinStorage::urlLibrary('abc', 1))
        ->toStartWith('https://fls.example.laravel.cloud/skin/abc.png');
});
