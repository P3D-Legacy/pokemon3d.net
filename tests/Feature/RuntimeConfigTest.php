<?php

use App\Models\GameVersion;
use App\Models\User;
use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Http;

test('logo alt text comes from application config', function () {
    config(['app.name' => 'Configured Name']);

    $html = Blade::render('<x-application-logo /><x-application-mark /><x-logo-icon /><x-logo-icon-large /><x-logo-small /><x-logo-large />');

    expect(substr_count($html, 'alt="Configured Name"'))->toBe(6);
});

test('environment banners follow the configured application environment', function () {
    $vite = Mockery::mock(Vite::class);
    $vite->shouldReceive('__invoke')->andReturn('');
    app()->instance(Vite::class, $vite);

    $appLayout = file_get_contents(resource_path('views/components/layouts/app.blade.php'));
    $guestLayout = file_get_contents(resource_path('views/components/layouts/guest.blade.php'));

    expect($appLayout)->toContain("app()->environment('staging')")
        ->and($appLayout)->not->toContain('env(')
        ->and($guestLayout)->toContain("app()->environment('local')")
        ->and($guestLayout)->not->toContain('env(');

    app()->detectEnvironment(fn () => 'staging');

    $guest = Blade::render('<x-layouts.guest>Hello</x-layouts.guest>');

    expect($guest)->toContain('QA: FOR TESTING ONLY')
        ->and($guest)->toContain('plausible.js');

    app()->detectEnvironment(fn () => 'production');

    $production = Blade::render('<x-layouts.guest>Hello</x-layouts.guest>');

    expect($production)->not->toContain('QA: FOR TESTING ONLY')
        ->and($production)->not->toContain('DEV MODE')
        ->and($production)->toContain('plausible.js');

    app()->detectEnvironment(fn () => 'local');

    $local = Blade::render('<x-layouts.guest>Hello</x-layouts.guest>');

    expect($local)->toContain('DEV MODE')
        ->and($local)->not->toContain('plausible.js');
});

test('github release sync reads the repository url from config', function () {
    config(['services.github.api_repo' => 'https://api.github.com/repos/example/game']);

    Http::fake([
        'https://api.github.com/repos/example/game/releases' => Http::response([
            [
                'tag_name' => '1.2.3',
                'name' => 'Version 1.2.3',
                'published_at' => '2026-01-02T00:00:00Z',
                'html_url' => 'https://github.com/example/game/releases/tag/1.2.3',
                'assets' => [
                    ['browser_download_url' => 'https://example.test/game.zip'],
                ],
            ],
        ]),
    ]);

    $this->artisan('github:syncrelease')
        ->assertSuccessful();

    $version = GameVersion::query()->where('version', '1.2.3')->first();

    expect($version)->not->toBeNull()
        ->and($version->title)->toBe('Version 1.2.3')
        ->and($version->download_url)->toBe('https://example.test/game.zip');
});

test('github release sync fails when github returns an error', function () {
    config(['services.github.api_repo' => 'https://api.github.com/repos/example/game']);

    Http::fake([
        'https://api.github.com/repos/example/game/releases' => Http::response('nope', 500),
    ]);

    $this->artisan('github:syncrelease')
        ->assertFailed();

    expect(GameVersion::query()->count())->toBe(0);
});

test('github release sync fails when the repository url is not configured', function () {
    config(['services.github.api_repo' => null]);

    $this->artisan('github:syncrelease')
        ->assertFailed();

    expect(GameVersion::query()->count())->toBe(0);
});

test('super admin command assigns the role from the configured email', function () {
    seedPermissions();

    $user = User::factory()->create([
        'email' => 'owner@example.test',
    ]);

    config(['services.super_admin.email' => 'owner@example.test']);

    $this->artisan('p3d:givesa')
        ->assertSuccessful();

    expect($user->fresh()->hasRole('super-admin'))->toBeTrue();
});

test('super admin command fails when the email is not configured', function () {
    config(['services.super_admin.email' => null]);

    $this->artisan('p3d:givesa')
        ->assertFailed();
});

test('tumblr import fails when credentials are not configured', function () {
    config([
        'services.tumblr.consumer_key' => null,
        'services.tumblr.consumer_secret' => null,
        'services.tumblr.oauth_token' => null,
        'services.tumblr.oauth_token_secret' => null,
    ]);

    $this->artisan('import:tumblr', ['blogname' => 'pokemon3d'])
        ->assertFailed();
});
