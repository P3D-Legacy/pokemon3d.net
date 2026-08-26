<?php

use AliBayat\LaravelCategorizable\Category;
use App\Models\GameVersion;
use App\Models\Resource;
use App\Models\ResourceUpdate;
use App\Models\User;
use App\Notifications\Resource\UpdateNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

function createResourceCategory(string $name = 'Maps'): Category
{
    $category = new Category([
        'name' => $name,
        'type' => 'default',
    ]);
    $category->saveAsRoot();

    return $category;
}

test('resource index is rendered with inertia', function () {
    $category = createResourceCategory();
    $resource = Resource::factory()->create();
    $resource->syncCategories($category);

    $this->get(route('resource.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/index')
            ->has('resources.data')
            ->has('categories')
            ->has('copy'));
});

test('resource category page is rendered with inertia', function () {
    $category = createResourceCategory('Textures');
    $resource = Resource::factory()->create();
    $resource->syncCategories($category);

    $this->get(route('resource.category', $category->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/index')
            ->where('selectedCategory.slug', $category->slug)
            ->has('resources.data', 1));
});

test('resource show is rendered with inertia', function () {
    $category = createResourceCategory();
    $resource = Resource::factory()->create([
        'description' => '# Hello resource',
    ]);
    $resource->syncCategories($category);

    $this->get(route('resource.uuid', $resource->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/show')
            ->where('resource.name', $resource->name)
            ->where('resource.uuid', $resource->uuid)
            ->has('copy.downloadDisclaimerTitle')
            ->has('copy.downloadDisclaimerBody')
            ->has('copy.downloadDisclaimerCancel')
            ->has('copy.downloadDisclaimerConfirm'));
});

test('guests are redirected from resource create', function () {
    $this->get(route('resource.create'))->assertRedirect(route('login'));
});

test('authenticated users can open resource create', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('resource.create'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/create')
            ->has('categories')
            ->has('copy'));
});

test('owner can open edit and delete pages', function () {
    $owner = User::factory()->create();
    $category = createResourceCategory();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $resource->syncCategories($category);

    $this->actingAs($owner)
        ->get(route('resource.edit', $resource->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/edit')
            ->where('resource.uuid', $resource->uuid));

    $this->actingAs($owner)
        ->get(route('resource.delete', $resource->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/delete')
            ->where('resource.uuid', $resource->uuid));
});

test('non owner cannot edit delete or post updates', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($other)
        ->get(route('resource.edit', $resource->uuid))
        ->assertForbidden();

    $this->actingAs($other)
        ->get(route('resource.delete', $resource->uuid))
        ->assertForbidden();

    $this->actingAs($other)
        ->get(route('resource.updates.create', $resource->uuid))
        ->assertForbidden();

    $this->actingAs($other)
        ->put(route('resource.update', $resource->uuid), [
            'name' => 'Hacked',
            'brief' => 'Hacked brief text',
            'description' => 'Hacked description text',
            'category' => 1,
        ])
        ->assertForbidden();
});

test('owner can update resource metadata', function () {
    $owner = User::factory()->create();
    $category = createResourceCategory();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $resource->syncCategories($category);

    $this->actingAs($owner)
        ->put(route('resource.update', $resource->uuid), [
            'name' => 'Updated Resource',
            'brief' => 'Updated brief content',
            'description' => 'Updated description content',
            'category' => $category->id,
        ])
        ->assertRedirect(route('resource.uuid', $resource->uuid));

    expect($resource->fresh()->name)->toBe('Updated Resource');
});

test('owner can post a resource update with zip file', function () {
    Storage::fake('resource');

    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $gameVersion = GameVersion::factory()->create();
    $file = UploadedFile::fake()->create('pack.zip', 100, 'application/zip');

    $this->actingAs($owner)
        ->get(route('resource.updates.create', $resource->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/updates/create')
            ->has('copy.externalDownloadUrl')
            ->has('copy.fileOrUrlHelp'));

    $this->actingAs($owner)
        ->post(route('resource.updates.store', $resource->uuid), [
            'version' => '1.0.0',
            'description' => 'First release of the pack',
            'gameversion' => $gameVersion->id,
            'file' => $file,
        ])
        ->assertRedirect(route('resource.uuid', $resource->uuid));

    $update = $resource->updates()->first();
    $media = $update->getFirstMedia('resource_update_file');

    expect($update->external_download_url)->toBeNull()
        ->and($media)->not->toBeNull()
        ->and($media->disk)->toBe('resource');
});

test('owner can post a resource update with an external download url', function () {
    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $gameVersion = GameVersion::factory()->create();
    $externalUrl = 'https://example.com/packs/large-mode.zip';

    $this->actingAs($owner)
        ->post(route('resource.updates.store', $resource->uuid), [
            'version' => '2.0.0',
            'description' => 'Large pack hosted externally',
            'gameversion' => $gameVersion->id,
            'external_download_url' => $externalUrl,
        ])
        ->assertRedirect(route('resource.uuid', $resource->uuid));

    $update = $resource->updates()->first();

    expect($update)->not->toBeNull()
        ->and($update->external_download_url)->toBe($externalUrl)
        ->and($update->getFirstMedia('resource_update_file'))->toBeNull();
});

test('resource update download redirects to external url and increments downloads', function () {
    $resource = Resource::factory()->create();
    $update = ResourceUpdate::factory()->create([
        'resource_id' => $resource->id,
        'downloads' => 3,
        'external_download_url' => 'https://example.com/packs/large-mode.zip',
    ]);

    $this->get(route('resource.updates.download', [
        'uuid' => $resource->uuid,
        'update' => $update->id,
    ]))
        ->assertRedirect('https://example.com/packs/large-mode.zip');

    expect($update->fresh()->downloads)->toBe(4);
});

test('resource update files are stored under the resource prefix on the object disk', function () {
    $objectRoot = storage_path('framework/testing-disks/object-resource');
    File::deleteDirectory($objectRoot);
    File::ensureDirectoryExists($objectRoot);

    config([
        'filesystems.disks.s3' => [
            'driver' => 'local',
            'root' => $objectRoot,
            'throw' => true,
        ],
        'filesystems.disks.resource' => [
            'driver' => 'scoped',
            'disk' => 's3',
            'prefix' => 'resource',
            'throw' => true,
        ],
    ]);

    Storage::forgetDisk(['s3', 'resource']);

    $update = ResourceUpdate::factory()->create([
        'external_download_url' => null,
    ]);

    $update
        ->addMedia(UploadedFile::fake()->create('pack.zip', 100, 'application/zip'))
        ->usingName('cool-pack.zip')
        ->toMediaCollection('resource_update_file');

    $media = $update->getFirstMedia('resource_update_file');

    expect($media)->not->toBeNull()
        ->and($media->disk)->toBe('resource')
        ->and($media->getPathRelativeToRoot())->toBe($media->id.'/'.$media->file_name)
        ->and(File::exists($objectRoot.'/resource/'.$media->id.'/'.$media->file_name))->toBeTrue();
});

test('resource update download streams uploaded file from the media disk and increments downloads', function () {
    Storage::fake('resource');

    $resource = Resource::factory()->create(['name' => 'Cool Pack']);
    $update = ResourceUpdate::factory()->create([
        'resource_id' => $resource->id,
        'title' => '1.0.0',
        'downloads' => 2,
        'external_download_url' => null,
    ]);

    $update
        ->addMedia(UploadedFile::fake()->create('pack.zip', 100, 'application/zip'))
        ->usingName('cool-pack-1.0.0.zip')
        ->toMediaCollection('resource_update_file');

    $response = $this->get(route('resource.updates.download', [
        'uuid' => $resource->uuid,
        'update' => $update->id,
    ]));

    $response->assertOk();
    expect($response->headers->get('content-disposition'))->toContain('cool-pack-1.0.0.zip')
        ->and($update->fresh()->downloads)->toBe(3);
});

test('resource update download flashes when media record exists but file is missing on disk', function () {
    Storage::fake('resource');

    $resource = Resource::factory()->create();
    $update = ResourceUpdate::factory()->create([
        'resource_id' => $resource->id,
        'external_download_url' => null,
    ]);

    $update
        ->addMedia(UploadedFile::fake()->create('pack.zip', 100, 'application/zip'))
        ->toMediaCollection('resource_update_file');

    $media = $update->getFirstMedia('resource_update_file');
    Storage::disk($media->disk)->delete($media->getPathRelativeToRoot());

    $this->get(route('resource.updates.download', [
        'uuid' => $resource->uuid,
        'update' => $update->id,
    ]))
        ->assertRedirect(route('resource.uuid', $resource->uuid))
        ->assertSessionHas('flash.banner', __('File not found on server!'));

    expect($update->fresh()->downloads)->toBe($update->downloads);
});

test('resource update store requires either a zip file or an external download url', function () {
    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $gameVersion = GameVersion::factory()->create();

    $this->actingAs($owner)
        ->post(route('resource.updates.store', $resource->uuid), [
            'version' => '1.0.0',
            'description' => 'Missing download source',
            'gameversion' => $gameVersion->id,
        ])
        ->assertSessionHasErrors(['file', 'external_download_url']);

    expect($resource->updates()->count())->toBe(0);
});

test('resource update store rejects both a zip file and an external download url', function () {
    Storage::fake('resource');

    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $gameVersion = GameVersion::factory()->create();
    $file = UploadedFile::fake()->create('pack.zip', 100, 'application/zip');

    $this->actingAs($owner)
        ->post(route('resource.updates.store', $resource->uuid), [
            'version' => '1.0.0',
            'description' => 'Both sources provided',
            'gameversion' => $gameVersion->id,
            'file' => $file,
            'external_download_url' => 'https://example.com/packs/large-mode.zip',
        ])
        ->assertSessionHasErrors(['file', 'external_download_url']);

    expect($resource->updates()->count())->toBe(0);
});

test('non owner can rate a resource and owner cannot', function () {
    $owner = User::factory()->create();
    $rater = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->get(route('resource.rate', $resource->uuid))
        ->assertForbidden();

    $this->actingAs($rater)
        ->get(route('resource.rate', $resource->uuid))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('resources/rate'));

    $this->actingAs($rater)
        ->post(route('resource.rate.store', $resource->uuid), [
            'rating' => 5,
            'body' => 'This resource is excellent.',
        ])
        ->assertRedirect(route('resource.uuid', $resource->uuid));

    expect($resource->fresh()->numberOfReviews())->toBe(1);
});

test('authenticated users can toggle likes', function () {
    $owner = User::factory()->create();
    $liker = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($liker)
        ->post(route('resource.like', $resource->uuid))
        ->assertRedirect();

    expect($resource->fresh()->isLikedBy($liker))->toBeTrue();
});

test('guests cannot follow resources or view following', function () {
    $resource = Resource::factory()->create();

    $this->post(route('resource.follow', $resource->uuid))->assertRedirect(route('login'));
    $this->get(route('resource.following'))->assertRedirect(route('login'));
});

test('authenticated users can toggle resource follows', function () {
    $owner = User::factory()->create();
    $follower = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($follower)
        ->post(route('resource.follow', $resource->uuid))
        ->assertRedirect();

    expect($resource->fresh()->isFollowedBy($follower))->toBeTrue();
    $this->assertDatabaseHas('resource_followers', [
        'user_id' => $follower->id,
        'resource_id' => $resource->id,
    ]);

    $this->actingAs($follower)
        ->post(route('resource.follow', $resource->uuid))
        ->assertRedirect();

    expect($resource->fresh()->isFollowedBy($follower))->toBeFalse();
    $this->assertDatabaseMissing('resource_followers', [
        'user_id' => $follower->id,
        'resource_id' => $resource->id,
    ]);
});

test('resource owners cannot follow their own resource when debug is disabled', function () {
    config(['app.debug' => false]);

    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->post(route('resource.follow', $resource->uuid))
        ->assertForbidden();
});

test('following page lists only followed resources', function () {
    $owner = User::factory()->create();
    $follower = User::factory()->create();
    $followed = Resource::factory()->create(['user_id' => $owner->id, 'name' => 'Followed Pack']);
    $other = Resource::factory()->create(['user_id' => $owner->id, 'name' => 'Other Pack']);

    $followed->followers()->attach($follower->id);

    $this->actingAs($follower)
        ->get(route('resource.following'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('resources/following')
            ->has('resources.data', 1)
            ->where('resources.data.0.uuid', $followed->uuid)
            ->where('resources.data.0.name', $followed->name)
            ->missing('resources.data.1'));

    expect($other->isFollowedBy($follower))->toBeFalse();
});

test('posting a resource update notifies followers but not the author', function () {
    Notification::fake();

    $owner = User::factory()->create();
    $follower = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);
    $gameVersion = GameVersion::factory()->create();

    $resource->followers()->attach($follower->id);

    $this->actingAs($owner)
        ->post(route('resource.updates.store', $resource->uuid), [
            'version' => '1.2.0',
            'description' => 'Follower-facing update',
            'gameversion' => $gameVersion->id,
            'external_download_url' => 'https://example.com/packs/update.zip',
        ])
        ->assertRedirect(route('resource.uuid', $resource->uuid));

    Notification::assertSentTo($follower, UpdateNotification::class);
    Notification::assertNotSentTo($owner, UpdateNotification::class);
});

test('resource update notification uses mail when email notifications consent is given', function () {
    $follower = User::factory()->create();
    $resource = Resource::factory()->create();
    $update = ResourceUpdate::factory()->create(['resource_id' => $resource->id]);

    $notification = new UpdateNotification($resource, $update);

    expect($notification->via($follower))->toBe(['database']);

    $follower->giveConsentTo('email.notifications');

    expect($notification->via($follower->fresh()))->toBe(['mail', 'database']);
});

test('owner can delete their resource', function () {
    $owner = User::factory()->create();
    $resource = Resource::factory()->create(['user_id' => $owner->id]);

    $this->actingAs($owner)
        ->delete(route('resource.destroy', $resource->uuid))
        ->assertRedirect(route('resource.index'));

    $this->assertSoftDeleted($resource);
});
