<?php

use App\Models\User;
use App\Support\CloudObjectStorage;

test('profile photo url uses the configured public disk url without requiring an s3 client', function () {
    config([
        'filesystems.disks.s3.region' => null,
        'filesystems.disks.s3.url' => 'https://cdn.example.com',
        'jetstream.profile_photo_disk' => 's3',
    ]);

    $user = User::factory()->create([
        'profile_photo_path' => 'profile-photos/avatar.jpg',
    ]);

    expect($user->profile_photo_url)->toBe('https://cdn.example.com/profile-photos/avatar.jpg');
});

test('profile photo url falls back to the default avatar when object storage is misconfigured', function () {
    config([
        'filesystems.disks.s3.region' => null,
        'filesystems.disks.s3.url' => null,
        'filesystems.disks.s3.key' => null,
        'filesystems.disks.s3.secret' => null,
        'filesystems.disks.s3.bucket' => null,
        'jetstream.profile_photo_disk' => 's3',
    ]);

    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'profile_photo_path' => 'profile-photos/avatar.jpg',
    ]);

    expect($user->profile_photo_url)->toStartWith('https://ui-avatars.com/api/?name=');
});

test('users without a profile photo still receive the default avatar url', function () {
    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'profile_photo_path' => null,
    ]);

    expect($user->profile_photo_url)->toStartWith('https://ui-avatars.com/api/?name=');
});

test('profile photo url falls back to the object public url when the disk url is empty', function () {
    config([
        'filesystems.disks.s3.url' => null,
        'filesystems.object_public_url' => 'https://fls.example.laravel.cloud',
        'jetstream.profile_photo_disk' => 's3',
    ]);

    $user = User::factory()->create([
        'profile_photo_path' => 'profile-photos/avatar.jpg',
    ]);

    expect($user->profile_photo_url)->toBe('https://fls.example.laravel.cloud/profile-photos/avatar.jpg');
});

test('profile photos follow the laravel cloud injected disk', function () {
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
        'filesystems.disks.s3.url' => 'https://pokemon3d.net.test/storage/object',
        'filesystems.disks.cloud' => [
            'driver' => 's3',
            'key' => 'key',
            'secret' => 'secret',
            'bucket' => 'bucket',
            'url' => 'https://fls.example.laravel.cloud',
            'endpoint' => 'https://example.invalid',
            'region' => 'auto',
        ],
        'jetstream.profile_photo_disk' => 's3',
    ]);

    CloudObjectStorage::configure();

    $user = User::factory()->create([
        'profile_photo_path' => 'profile-photos/avatar.jpg',
    ]);

    expect(config('jetstream.profile_photo_disk'))->toBe('cloud')
        ->and($user->profile_photo_url)->toBe('https://fls.example.laravel.cloud/profile-photos/avatar.jpg');

    unset($_SERVER['LARAVEL_CLOUD_DISK_CONFIG']);
});

test('profile photos follow the filesystem disk injected by the environment', function () {
    config([
        'filesystems.disks.cloud.region' => 'auto',
        'filesystems.disks.cloud.url' => 'https://fls.example.laravel.cloud',
        'jetstream.profile_photo_disk' => 'cloud',
    ]);

    $user = User::factory()->create([
        'profile_photo_path' => 'profile-photos/avatar.jpg',
    ]);

    expect($user->profile_photo_url)->toBe('https://fls.example.laravel.cloud/profile-photos/avatar.jpg');
});
