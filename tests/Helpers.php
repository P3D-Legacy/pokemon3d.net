<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\Sanctum;

function seedPermissions(): void
{
    test()->seed(PermissionSeeder::class);
}

function actingAsApiUser(User $user, array $permissions = []): User
{
    $permissionNames = array_unique(array_merge(['api'], $permissions));
    $user->givePermissionTo($permissionNames);
    Sanctum::actingAs($user);

    return $user;
}

function createApiUserWithPermissions(array $permissions = []): User
{
    $user = User::factory()->create();

    return actingAsApiUser($user, $permissions);
}

function writeOpenApiFixture(): void
{
    $dir = storage_path('app/scribe');
    File::ensureDirectoryExists($dir);
    File::put($dir.'/openapi.json', json_encode([
        'openapi' => '3.0.0',
        'info' => ['title' => 'Test', 'version' => '1.0.0'],
        'paths' => [],
    ]));
}

function ensurePublicBadgePngFixture(): void
{
    $dir = public_path('img/badge');
    File::ensureDirectoryExists($dir);
    $path = $dir.'/Test_Badge.png';
    if (! File::exists($path)) {
        File::put($path, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg=='));
    }
}
