<?php

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('moderator can list and create posts', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Post::factory()->count(2)->create();

    Livewire::test(ListPosts::class)
        ->assertSuccessful();

    Livewire::test(CreatePost::class)
        ->fillForm([
            'title' => 'Admin Post',
            'body' => '## Hello',
            'published_at' => now()->toDateString(),
            'active' => true,
            'sticky' => false,
            'user_id' => $user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(Post::query()->where('title', 'Admin Post')->exists())->toBeTrue();
});

test('moderator can list and create categories', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    Category::factory()->create();

    Livewire::test(ListCategories::class)
        ->assertSuccessful();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'News',
            'parent_id' => null,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(Category::query()->where('name', 'News')->exists())->toBeTrue();
});
