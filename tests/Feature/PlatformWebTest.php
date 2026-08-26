<?php

use AliBayat\LaravelCategorizable\Category;
use App\Models\GamejoltAccount;
use App\Models\Post;
use App\Models\Resource;
use App\Models\Skin;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia as Assert;

test('public pages respond successfully', function () {
    foreach ([
        route('home'),
        route('legal'),
        route('contact'),
        route('resource.index'),
        route('member.index'),
        route('server.index'),
        route('review'),
        route('skins-newest'),
        route('skins-popular'),
    ] as $url) {
        $this->get($url)->assertOk();
    }

    $this->get(route('download'))->assertRedirect();
});

test('external redirect routes point off-site', function () {
    $wiki = $this->get(route('wiki'));
    $wiki->assertRedirect();
    expect(str_contains((string) $wiki->headers->get('Location'), 'wiki.pokemon3d.net'))->toBeTrue();

    $forum = $this->get(route('forum'));
    $forum->assertRedirect();
    expect(str_contains((string) $forum->headers->get('Location'), 'forum.pokemon3d.net'))->toBeTrue();

    $github = $this->get(route('github'));
    $github->assertRedirect();
    expect(str_contains((string) $github->headers->get('Location'), 'github.com'))->toBeTrue();

    $invite = config('services.discord.invite_url');
    expect($invite)->not->toBeEmpty();
    $this->get(route('discord'))->assertRedirect($invite);
});

test('external redirect routes work for inertia visits', function () {
    $version = hash_file('xxh128', public_path('build/manifest.json'));

    $headers = [
        'X-Inertia' => 'true',
        'X-Inertia-Version' => $version,
        'X-Requested-With' => 'XMLHttpRequest',
    ];

    $wiki = $this->get(route('wiki'), $headers);
    $wiki->assertStatus(409);
    expect(str_contains((string) $wiki->headers->get('X-Inertia-Location'), 'wiki.pokemon3d.net'))->toBeTrue();

    $forum = $this->get(route('forum'), $headers);
    $forum->assertStatus(409);
    expect(str_contains((string) $forum->headers->get('X-Inertia-Location'), 'forum.pokemon3d.net'))->toBeTrue();

    $github = $this->get(route('github'), $headers);
    $github->assertStatus(409);
    expect(str_contains((string) $github->headers->get('X-Inertia-Location'), 'github.com'))->toBeTrue();

    $invite = config('services.discord.invite_url');
    expect($invite)->not->toBeEmpty();

    $discord = $this->get(route('discord'), $headers);
    $discord->assertStatus(409);
    expect($discord->headers->get('X-Inertia-Location'))->toBe($invite);
});

test('blog index and show render', function () {
    $this->get(route('blog.index'))->assertOk();

    $post = Post::factory()->create([
        'active' => true,
        'published_at' => now()->subDay(),
    ]);

    $this->get(route('blog.show', $post->slug))->assertOk();
});

test('member profile page renders by username', function () {
    $user = User::factory()->create();

    $this->get(route('member.show', $user->username))->assertOk();
});

test('resource show page renders for published resource', function () {
    $category = new Category([
        'name' => 'Test Category',
        'type' => 'default',
    ]);
    $category->saveAsRoot();

    $resource = Resource::factory()->create();
    $resource->categories()->attach($category->id);

    $this->get(route('resource.uuid', $resource->uuid))->assertOk();
});

test('public skin show renders', function () {
    $root = storage_path('framework/testing-disks/skin');
    File::ensureDirectoryExists($root);
    config([
        'filesystems.disks.skin' => [
            'driver' => 'local',
            'root' => $root,
            'throw' => false,
        ],
    ]);

    $skin = Skin::factory()->create();
    File::put($root.'/'.$skin->path(), 'fake');

    $this->get(route('skin-show', $skin))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/show'));
});

test('guests are redirected from authenticated routes', function () {
    $this->get(route('resource.create'))->assertRedirect(route('login'));
    $this->get(route('skin-home'))->assertRedirect(route('login'));
    $this->get(route('notifications.index'))->assertRedirect(route('login'));
});

test('authenticated user without gamejolt is redirected from skin home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('skin-home'))
        ->assertRedirect(route('profile.show'))
        ->assertSessionHas('flash.bannerStyle', 'warning');
});

test('authenticated user with gamejolt can open skin home', function () {
    $root = storage_path('framework/testing-disks/player');
    File::ensureDirectoryExists($root);
    config([
        'filesystems.disks.player' => [
            'driver' => 'local',
            'root' => $root,
            'throw' => false,
        ],
        'filesystems.disks.skin' => [
            'driver' => 'local',
            'root' => storage_path('framework/testing-disks/skin'),
            'throw' => false,
        ],
    ]);
    File::ensureDirectoryExists(storage_path('framework/testing-disks/skin'));

    $user = User::factory()->create();
    GamejoltAccount::factory()->create(['user_id' => $user->id]);

    $this->actingAs($user)
        ->get(route('skin-home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('skins/home'));
});
