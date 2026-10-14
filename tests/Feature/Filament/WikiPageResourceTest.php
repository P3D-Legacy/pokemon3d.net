<?php

use App\Filament\Resources\WikiPageResource\Pages\CreateWikiPage;
use App\Filament\Resources\WikiPageResource\Pages\ListWikiPages;
use App\Models\User;
use App\Models\WikiPage;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function (): void {
    seedPermissions();
    Filament::setCurrentPanel(Filament::getDefaultPanel());
});

test('moderator can list and create wiki pages', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
    $user->assignRole('moderator');

    $this->actingAs($user);

    WikiPage::factory()->count(2)->create();

    Livewire::test(ListWikiPages::class)
        ->assertSuccessful();

    Livewire::test(CreateWikiPage::class)
        ->fillForm([
            'title' => 'Admin Guide',
            'body' => '## Hello wiki',
            'published' => true,
            'user_id' => $user->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $page = WikiPage::query()->where('title', 'Admin Guide')->first();

    expect($page)->not->toBeNull()
        ->and($page->slug)->toBe('admin-guide')
        ->and($page->revisions)->toHaveCount(1);
});
