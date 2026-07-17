<?php

use App\Models\GamejoltAccount;
use App\Models\Skin;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia as Assert;

function configureSkinDisk(): string
{
    $root = storage_path('framework/testing-disks/skin');
    File::ensureDirectoryExists($root);
    config([
        'filesystems.disks.skin' => [
            'driver' => 'local',
            'root' => $root,
            'throw' => false,
        ],
        'filesystems.disks.player' => [
            'driver' => 'local',
            'root' => storage_path('framework/testing-disks/player'),
            'throw' => false,
        ],
    ]);
    File::ensureDirectoryExists(storage_path('framework/testing-disks/player'));

    return $root;
}

test('public skins newest and popular render with inertia', function () {
    configureSkinDisk();

    $this->get(route('skins-newest'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/public/newest')
            ->has('skins.data'));

    $this->get(route('skins-popular'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/public/popular')
            ->has('skins.data'));
});

test('public skin show renders with inertia', function () {
    $root = configureSkinDisk();
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
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('skin-home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/home')
            ->has('skins')
            ->has('currentSkin')
            ->has('slots')
            ->has('deleteActivity')
            ->has('canImport'));
});

test('skin mutating routes reject get requests', function () {
    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);
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
