<?php

use App\Models\User;

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
