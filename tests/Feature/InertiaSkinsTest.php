<?php

use App\Models\GamejoltAccount;
use App\Models\Skin;
use App\Models\User;
use App\Support\SkinPresenter;
use App\Support\SkinStorage;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;

function configureSkinDisk(): array
{
    $skinRoot = storage_path('framework/testing-disks/skin');
    $playerRoot = storage_path('framework/testing-disks/player');
    File::ensureDirectoryExists($skinRoot);
    File::ensureDirectoryExists($playerRoot);

    config([
        'filesystems.disks.skin' => [
            'driver' => 'local',
            'root' => $skinRoot,
            'url' => 'http://skin.test',
            'throw' => true,
        ],
        'filesystems.disks.player' => [
            'driver' => 'local',
            'root' => $playerRoot,
            'url' => rtrim((string) config('app.url'), '/').'/player',
            'throw' => true,
        ],
        'skins.max_upload' => 10,
        'skins.width' => 96,
        'skins.height' => 128,
    ]);

    return [$skinRoot, $playerRoot];
}

function createVerifiedUserWithGamejolt(): array
{
    $user = User::factory()->create();
    $gamejolt = GamejoltAccount::factory()->create(['user_id' => $user->id]);

    return [$user, $gamejolt];
}

function fakeSkinPng(int $width = 96, int $height = 128): UploadedFile
{
    return UploadedFile::fake()->image('skin.png', $width, $height);
}

test('public skins newest and popular render with inertia', function () {
    configureSkinDisk();

    $this->get(route('skins-newest'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/public/index')
            ->where('sort', 'newest')
            ->has('skins.data'));

    $this->get(route('skins-popular'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/public/index')
            ->where('sort', 'popular')
            ->has('skins.data'));
});

test('public skin show renders with inertia', function () {
    [$root] = configureSkinDisk();
    $skin = Skin::factory()->create();
    File::put($root.'/'.$skin->path(), 'fake');

    $this->get(route('skin-show', $skin))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/show')
            ->where('skin.uuid', $skin->uuid)
            ->where('skin.name', $skin->name));
});

test('guests are redirected from skin home', function () {
    $this->get(route('skin-home'))->assertRedirect(route('login'));
});

test('authenticated user without gamejolt is redirected from skin home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('skin-home'))
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('flash.bannerStyle', 'warning');
});

test('authenticated user with gamejolt can open skin home with inertia', function () {
    configureSkinDisk();
    [$user] = createVerifiedUserWithGamejolt();

    $this->actingAs($user)
        ->get(route('skin-home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/home')
            ->has('skins')
            ->has('currentSkin')
            ->has('slots')
            ->has('deleteActivity')
            ->has('canImport')
            ->has('width')
            ->has('height'));
});

test('skin mutating routes reject get requests', function () {
    [$user] = createVerifiedUserWithGamejolt();
    $skin = Skin::factory()->create();

    $this->actingAs($user)
        ->get(route('skin-like', $skin->uuid))
        ->assertMethodNotAllowed();

    $this->actingAs($user)
        ->get(route('skin-apply', $skin->uuid))
        ->assertMethodNotAllowed();

    $this->actingAs($user)
        ->get(route('skin-destroy', $skin->uuid))
        ->assertMethodNotAllowed();

    $this->actingAs($user)
        ->get(route('player-skin-destroy'))
        ->assertMethodNotAllowed();

    $this->actingAs($user)
        ->get(route('player-skin-duplicate'))
        ->assertMethodNotAllowed();
});

test('user can upload a valid library skin', function () {
    configureSkinDisk();
    [$user, $gamejolt] = createVerifiedUserWithGamejolt();

    $this->actingAs($user)
        ->post(route('skin-store'), [
            'name' => 'Test Skin',
            'image' => fakeSkinPng(),
            'public' => '1',
            'rules' => '1',
        ])
        ->assertRedirect(route('skins-my'));

    $skin = Skin::query()->where('owner_id', $gamejolt->id)->first();
    expect($skin)->not->toBeNull()
        ->and($skin->name)->toBe('Test Skin')
        ->and(SkinStorage::existsLibrary($skin->uuid))->toBeTrue();
});

test('skin upload rejects wrong dimensions', function () {
    configureSkinDisk();
    [$user] = createVerifiedUserWithGamejolt();

    $this->actingAs($user)
        ->post(route('skin-store'), [
            'name' => 'Bad Skin',
            'image' => fakeSkinPng(64, 64),
            'rules' => '1',
        ])
        ->assertSessionHasErrors('image');

    expect(Skin::query()->count())->toBe(0);
});

test('owner can delete a skin even when the file is missing', function () {
    configureSkinDisk();
    [$user, $gamejolt] = createVerifiedUserWithGamejolt();
    $skin = Skin::factory()->create([
        'owner_id' => $gamejolt->id,
        'user_id' => $user->id,
    ]);

    expect(SkinStorage::existsLibrary($skin->uuid))->toBeFalse();

    $this->actingAs($user)
        ->delete(route('skin-destroy', $skin->uuid))
        ->assertRedirect(route('skins-my'));

    expect(Skin::query()->whereKey($skin->id)->exists())->toBeFalse();
});

test('apply copies a public skin to the player disk', function () {
    configureSkinDisk();
    [$user, $gamejolt] = createVerifiedUserWithGamejolt();
    $skin = Skin::factory()->create(['public' => true]);
    SkinStorage::storeLibrary(fakeSkinPng(), $skin->uuid);

    $this->actingAs($user)
        ->post(route('skin-apply', $skin->uuid))
        ->assertRedirect(route('skin-home'));

    expect(SkinStorage::existsPlayer($gamejolt->id))->toBeTrue()
        ->and(SkinStorage::urlPlayer($gamejolt->id, 1))
        ->toContain('/player/'.$gamejolt->id.'.png');
});

test('apply rejects private skins the viewer does not own', function () {
    configureSkinDisk();
    [$user] = createVerifiedUserWithGamejolt();
    $skin = Skin::factory()->create(['public' => false]);
    SkinStorage::storeLibrary(fakeSkinPng(), $skin->uuid);

    $this->actingAs($user)
        ->post(route('skin-apply', $skin->uuid))
        ->assertForbidden();
});

test('like rejects private skins the viewer does not own', function () {
    configureSkinDisk();
    [$user] = createVerifiedUserWithGamejolt();
    $skin = Skin::factory()->create(['public' => false]);

    $this->actingAs($user)
        ->post(route('skin-like', $skin->uuid))
        ->assertForbidden();
});

test('player skins admin routes require permission', function () {
    configureSkinDisk();
    $this->seed(PermissionSeeder::class);
    [$user] = createVerifiedUserWithGamejolt();

    $this->actingAs($user)
        ->get(route('player-skins'))
        ->assertForbidden();

    $user->givePermissionTo('skin-player-destroy');

    $this->actingAs($user)
        ->get(route('player-skins'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('skins/player'));
});

test('skin presenter uses storage disk urls without size lookups', function () {
    configureSkinDisk();
    $skin = Skin::factory()->create();
    SkinStorage::storeLibrary(fakeSkinPng(), $skin->uuid);

    $card = SkinPresenter::card($skin);

    expect($card['image_url'])->toStartWith('http://skin.test/')
        ->and($card['image_url'])->toContain($skin->uuid.'.png')
        ->and($card['image_url'])->not->toContain('/img/skin/')
        ->and($card['file_size'])->toBe('N/A');
});

test('skin storage existence check does not throw when the disk fails', function () {
    config([
        'filesystems.disks.skin' => [
            'driver' => 's3',
            'key' => 'invalid',
            'secret' => 'invalid',
            'region' => 'auto',
            'bucket' => 'invalid-bucket',
            'url' => 'https://cdn.example.test',
            'endpoint' => 'https://example.invalid',
            'use_path_style_endpoint' => true,
            'throw' => true,
            'root' => 'skin',
        ],
    ]);

    expect(SkinStorage::existsLibrary('9f788b03-65d5-4420-90e8-d71e80f69fa7'))->toBeFalse()
        ->and(SkinStorage::sizeLibrary('9f788b03-65d5-4420-90e8-d71e80f69fa7'))->toBeNull();
});

test('unready library disk returns placeholder url and rejects writes', function () {
    $playerRoot = storage_path('framework/testing-disks/player-unready');
    File::ensureDirectoryExists($playerRoot);

    config([
        'filesystems.disks.s3' => [
            'driver' => 's3',
            'key' => null,
            'secret' => null,
            'region' => 'auto',
            'bucket' => null,
            'url' => null,
            'endpoint' => null,
            'throw' => true,
        ],
        'filesystems.disks.skin' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'skin',
            'throw' => true,
        ],
        'filesystems.disks.player' => [
            'driver' => 'local',
            'root' => $playerRoot,
            'url' => rtrim((string) config('app.url'), '/').'/player',
            'throw' => true,
        ],
        'skins.width' => 96,
        'skins.height' => 128,
    ]);

    $uuid = '9f788b03-65d5-4420-90e8-d71e80f69fa7';

    expect(SkinStorage::urlLibrary($uuid, 123))
        ->toBe(SkinStorage::placeholderUrl())
        ->and(SkinStorage::urlLibrary($uuid, 123))->not->toContain('/img/skin/')
        ->and(SkinStorage::existsLibrary($uuid))->toBeFalse();

    expect(fn () => SkinStorage::storeLibrary(fakeSkinPng(), $uuid))
        ->toThrow(RuntimeException::class);

    SkinStorage::storePlayer(fakeSkinPng(), 42);
    expect(SkinStorage::existsPlayer(42))->toBeTrue()
        ->and(SkinStorage::urlPlayer(42, 1))->toContain('/player/42.png');
});

test('scoped skin disks build library urls from the cloud parent disk url', function () {
    $playerRoot = storage_path('framework/testing-disks/player-cloud');
    File::ensureDirectoryExists($playerRoot);

    config([
        'filesystems.disks.s3' => [
            'driver' => 's3',
            'key' => 'key',
            'secret' => 'secret',
            'region' => 'auto',
            'bucket' => 'bucket',
            'url' => 'https://cdn.example.test',
            'endpoint' => 'https://example.invalid',
            'throw' => true,
        ],
        'filesystems.disks.skin' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'skin',
            'throw' => true,
        ],
        'filesystems.disks.player' => [
            'driver' => 'local',
            'root' => $playerRoot,
            'url' => rtrim((string) config('app.url'), '/').'/player',
            'throw' => true,
        ],
    ]);

    expect(SkinStorage::urlLibrary('abc', 1))
        ->toStartWith('https://cdn.example.test/skin/abc.png')
        ->and(SkinStorage::urlPlayer(42, 1))
        ->toContain('/player/42.png')
        ->and(SkinStorage::urlPlayer(42, 1))
        ->not->toContain('cdn.example.test');
});

test('import rejects skins with wrong dimensions', function () {
    configureSkinDisk();
    [$user, $gamejolt] = createVerifiedUserWithGamejolt();

    $png = fakeSkinPng(64, 64)->get();

    Http::fake([
        '*' => Http::response($png, 200, ['Content-Type' => 'image/png']),
    ]);

    $this->actingAs($user)
        ->post(route('import', $gamejolt->id))
        ->assertRedirect(route('skin-home'))
        ->assertSessionHas('error', 'Skin was not in a valid format!');

    expect(SkinStorage::existsPlayer($gamejolt->id))->toBeFalse();
});

test('import accepts a valid png and writes the player disk', function () {
    configureSkinDisk();
    [$user, $gamejolt] = createVerifiedUserWithGamejolt();

    $png = fakeSkinPng()->get();

    Http::fake([
        '*' => Http::response($png, 200, ['Content-Type' => 'image/png']),
    ]);

    $this->actingAs($user)
        ->post(route('import', $gamejolt->id))
        ->assertRedirect(route('skin-home'))
        ->assertSessionHas('flash.bannerStyle', 'success');

    expect(SkinStorage::existsPlayer($gamejolt->id))->toBeTrue();
});
