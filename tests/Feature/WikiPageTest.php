<?php

use App\Models\User;
use App\Models\WikiPage;
use App\Models\WikiRedirect;
use App\Support\WikiDiff;
use App\Support\WikiLinkRenderer;
use Inertia\Testing\AssertableInertia as Assert;

function wikiEditor(): User
{
    seedPermissions();

    $user = User::factory()->create();
    $user->assignRole('wiki-editor');

    return $user;
}

test('wiki index is public and lists published pages', function () {
    $page = WikiPage::factory()->create(['title' => 'Requirements', 'slug' => 'requirements']);
    WikiPage::factory()->unpublished()->create(['title' => 'Hidden']);

    $this->get(route('wiki'))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->component('wiki/index')
            ->has('entries.data', 1)
            ->where('entries.data.0.slug', $page->slug)
            ->where('canCreate', false));
});

test('wiki show renders markdown and wiki links', function () {
    WikiPage::factory()->create([
        'title' => 'Requirements',
        'slug' => 'requirements',
        'body' => 'See [[Requirements]] for details.',
    ]);

    $this->get(route('wiki.show', 'requirements'))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->component('wiki/show')
            ->where('entry.title', 'Requirements')
            ->where('entry.slug', 'requirements')
            ->has('entry.body_html'));
});

test('unpublished wiki pages are hidden from guests', function () {
    $page = WikiPage::factory()->unpublished()->create();

    $this->get(route('wiki.show', $page->slug))->assertNotFound();
});

test('trusted editors can view unpublished pages', function () {
    $page = WikiPage::factory()->unpublished()->create();

    $this->actingAs(wikiEditor())
        ->get(route('wiki.show', $page->slug))
        ->assertOk();
});

test('guests are redirected from wiki create', function () {
    $this->get(route('wiki.create'))->assertRedirect(route('login'));
});

test('verified users without the wiki role cannot create pages', function () {
    seedPermissions();
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('wiki.create'))
        ->assertForbidden();
});

test('trusted editors can create a wiki page with a revision', function () {
    $editor = wikiEditor();

    $this->actingAs($editor)
        ->post(route('wiki.store'), [
            'title' => 'Download notes',
            'body' => '## Notes\n\nBring a mouse.',
            'published' => true,
            'tags' => 'Guides, Download',
            'summary' => 'First draft',
        ])
        ->assertRedirect(route('wiki.show', 'download-notes'));

    $page = WikiPage::query()->where('slug', 'download-notes')->first();

    expect($page)->not->toBeNull()
        ->and($page->title)->toBe('Download notes')
        ->and($page->tags->pluck('name')->all())->toContain('Guides')
        ->and($page->revisions)->toHaveCount(1)
        ->and($page->revisions->first()->summary)->toBe('First draft');
});

test('trusted editors can update a page and keep history', function () {
    $editor = wikiEditor();
    $page = WikiPage::factory()->create([
        'title' => 'Old title',
        'slug' => 'old-title',
        'body' => 'Old body',
    ]);
    $page->recordRevision($editor, 'Original');

    $this->actingAs($editor)
        ->put(route('wiki.update', $page->slug), [
            'title' => 'New title',
            'body' => 'New body',
            'published' => true,
            'summary' => 'Rewrote it',
        ])
        ->assertRedirect();

    expect($page->fresh()->title)->toBe('New title')
        ->and($page->fresh()->revisions)->toHaveCount(2);
});

test('wiki redirects resolve to the target page', function () {
    $page = WikiPage::factory()->create([
        'title' => 'Ancient Power',
        'slug' => 'ancient-power',
    ]);
    WikiRedirect::factory()->create([
        'from_slug' => 'ancientpower',
        'redirectable_id' => $page->id,
        'redirectable_type' => WikiPage::class,
    ]);

    $this->get(route('wiki.show', 'ancientpower'))
        ->assertRedirect(route('wiki.show', 'ancient-power'));
});

test('wiki history and revision pages render', function () {
    $page = WikiPage::factory()->create(['slug' => 'history-page', 'body' => "one\n"]);
    $first = $page->recordRevision(null, 'First');
    $page->update(['body' => "one\ntwo"]);
    $second = $page->recordRevision(null, 'Second');

    $this->get(route('wiki.history', $page->slug))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->component('wiki/history')
            ->has('revisions', 2));

    $this->get(route('wiki.revision', [$page->slug, $second]))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->component('wiki/revision')
            ->has('diff')
            ->where('revision.id', $second->id));

    expect($first->id)->toBeInt();
});

test('wiki search finds matching titles', function () {
    WikiPage::factory()->create(['title' => 'Johto Gyms', 'slug' => 'johto-gyms']);
    WikiPage::factory()->create(['title' => 'Kanto Foxes', 'slug' => 'kanto-foxes']);

    $this->get(route('wiki', ['q' => 'Johto']))
        ->assertOk()
        ->assertInertia(fn (Assert $inertia) => $inertia
            ->has('entries.data', 1)
            ->where('entries.data.0.slug', 'johto-gyms'));
});

test('wiki links are rewritten to local urls', function () {
    WikiPage::factory()->create([
        'title' => 'Requirements',
        'slug' => 'requirements',
    ]);

    expect(WikiLinkRenderer::render('Read [[Requirements]] first'))
        ->toContain(route('wiki.show', 'requirements'));
});

test('wiki diffs mark added and removed lines', function () {
    $diff = WikiDiff::lines("alpha\nbeta", "alpha\ngamma");

    expect($diff)->toContain(['type' => 'equal', 'text' => 'alpha'])
        ->and($diff)->toContain(['type' => 'remove', 'text' => 'beta'])
        ->and($diff)->toContain(['type' => 'add', 'text' => 'gamma']);
});
