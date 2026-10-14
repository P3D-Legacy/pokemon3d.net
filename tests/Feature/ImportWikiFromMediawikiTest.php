<?php

use App\Models\PokemonSpecies;
use App\Models\User;
use App\Models\WikiPage;
use App\Models\WikiRedirect;
use App\Support\MediaWikiToMarkdown;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * @return array<string, mixed>
 */
function queryFromHttpRequest(Request $request): array
{
    parse_str(parse_url($request->url(), PHP_URL_QUERY) ?: '', $query);

    return $query;
}

test('mediawiki import converts prose pages and stores a revision', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    Http::fake(function ($request) {
        $query = queryFromHttpRequest($request);

        if (($query['list'] ?? null) === 'allpages' && ($query['apfilterredir'] ?? null) !== 'redirects') {
            return Http::response([
                'query' => [
                    'allpages' => [
                        ['title' => 'Requirements'],
                    ],
                ],
            ], 200);
        }

        if (($query['list'] ?? null) === 'allpages') {
            return Http::response(['query' => ['allpages' => []]], 200);
        }

        if (($query['action'] ?? null) === 'parse') {
            return Http::response([
                'parse' => [
                    'title' => 'Requirements',
                    'wikitext' => ['*' => "== Requirements ==\n\n'''Windows''' is required.\n\n* Keyboard\n"],
                    'images' => [],
                ],
            ], 200);
        }

        return Http::response(['query' => []], 200);
    });

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->assertSuccessful();

    $page = WikiPage::query()->where('slug', 'requirements')->first();

    expect($page)->not->toBeNull()
        ->and($page->body)->toContain('## Requirements')
        ->and($page->body)->toContain('**Windows**')
        ->and($page->revisions)->toHaveCount(1)
        ->and(User::query()->where('email', 'wiki-import@pokemon3d.net')->exists())->toBeTrue();
});

test('mediawiki import creates redirects for encyclopedia titles', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    PokemonSpecies::factory()->create([
        'name' => 'Abra',
        'slug' => 'abra',
    ]);

    Http::fake(function ($request) {
        $query = queryFromHttpRequest($request);

        if (($query['list'] ?? null) === 'allpages' && ($query['apfilterredir'] ?? null) === 'nonredirects') {
            return Http::response([
                'query' => [
                    'allpages' => [
                        ['title' => 'Abra'],
                    ],
                ],
            ], 200);
        }

        if (($query['action'] ?? null) === 'parse') {
            return Http::response([
                'parse' => [
                    'wikitext' => ['*' => "{{PokémonV2|ID=63}}\n"],
                    'images' => [],
                ],
            ], 200);
        }

        return Http::response(['query' => ['allpages' => []]], 200);
    });

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->assertSuccessful();

    expect(WikiRedirect::query()->where('from_slug', 'abra')->exists())->toBeTrue()
        ->and(WikiPage::query()->where('slug', 'abra')->exists())->toBeFalse();
});

test('mediawiki converter turns headings lists and links into markdown', function () {
    $markdown = MediaWikiToMarkdown::convert("== Places ==\n\nVisit [[Azalea Town|Azalea]] and [https://example.com the site].\n* One\n");

    expect($markdown)->toContain('## Places')
        ->and($markdown)->toContain('[[Azalea Town|Azalea]]')
        ->and($markdown)->toContain('[the site](https://example.com)')
        ->and($markdown)->toContain('- One');
});

test('mediawiki converter detects redirects', function () {
    expect(MediaWikiToMarkdown::isRedirect('#REDIRECT [[Ancient Power]]'))->toBe('Ancient Power');
});

/**
 * @param  array<string, mixed>  $overrides
 */
function fakeMediawikiImport(array $overrides = []): void
{
    $title = $overrides['title'] ?? 'Requirements';
    $wikitext = $overrides['wikitext'] ?? "== Requirements ==\n\n'''Windows''' is required.\n\n* Keyboard\n";
    $user = $overrides['user'] ?? 'Nilllzz';
    $blocks = $overrides['blocks'] ?? [];

    Http::preventStrayRequests();

    Http::fake(function (Request $request) use ($title, $wikitext, $user, $blocks) {
        $query = queryFromHttpRequest($request);

        if (($query['list'] ?? null) === 'blocks') {
            return Http::response([
                'query' => [
                    'blocks' => $blocks,
                ],
            ], 200);
        }

        if (($query['list'] ?? null) === 'allpages' && ($query['apfilterredir'] ?? null) !== 'redirects') {
            return Http::response([
                'query' => [
                    'allpages' => [
                        ['title' => $title],
                    ],
                ],
            ], 200);
        }

        if (($query['list'] ?? null) === 'allpages') {
            return Http::response(['query' => ['allpages' => []]], 200);
        }

        if (($query['action'] ?? null) === 'parse') {
            return Http::response([
                'parse' => [
                    'title' => $title,
                    'wikitext' => ['*' => $wikitext],
                    'images' => [],
                ],
            ], 200);
        }

        if (($query['prop'] ?? null) === 'revisions') {
            return Http::response([
                'query' => [
                    'pages' => [
                        '1' => [
                            'title' => $title,
                            'revisions' => [
                                ['user' => $user],
                            ],
                        ],
                    ],
                ],
            ], 200);
        }

        return Http::response(['query' => []], 200);
    });
}

test('mediawiki import skips commercial spam pages', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    fakeMediawikiImport([
        'title' => 'Fast Burn Keto: Avoiding Common Mistakes While Using Keto Supplements',
        'wikitext' => 'Fast Burn Keto is a dietary supplement that claims to help people achieve their weight loss goals.',
        'user' => 'FastBurnKetoReviews',
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->expectsOutputToContain('Skipped spam page')
        ->assertSuccessful();

    expect(WikiPage::query()->where('title', 'Fast Burn Keto: Avoiding Common Mistakes While Using Keto Supplements')->exists())->toBeFalse();
});

test('mediawiki import skips pages created by blocked users', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    fakeMediawikiImport([
        'title' => 'Random Notes',
        'wikitext' => 'Just leftover text with no game information.',
        'user' => 'HassanCramsie68',
        'blocks' => [
            ['user' => 'HassanCramsie68'],
        ],
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->expectsOutputToContain('Skipped spam page')
        ->assertSuccessful();

    expect(WikiPage::query()->where('title', 'Random Notes')->exists())->toBeFalse();
});

test('mediawiki import removes previously imported spam pages', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    $importer = User::factory()->create([
        'email' => 'wiki-import@pokemon3d.net',
        'username' => 'wiki-import',
    ]);

    $page = WikiPage::factory()->create([
        'title' => 'Easy IRA Plan',
        'slug' => 'easy-ira-plan',
        'body' => 'A broad choice of gold and silver coins for IRAs.',
        'user_id' => $importer->id,
    ]);

    fakeMediawikiImport([
        'title' => 'Easy IRA Plan',
        'wikitext' => 'A broad choice of gold and silver coins, every of which is IRS-authorized for IRAs.',
        'user' => 'GoldcoReviews',
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->assertSuccessful();

    $this->assertSoftDeleted($page);
});

test('mediawiki import keeps a local game page when mediawiki later looks like spam', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    $page = WikiPage::factory()->create([
        'title' => 'Azalea Town',
        'slug' => 'azalea-town',
        'body' => 'Azalea Town is a town in Johto. Visit the gym leader after speaking with Professor Elm.',
    ]);

    fakeMediawikiImport([
        'title' => 'Azalea Town',
        'wikitext' => 'Buy Fast Burn Keto today and start your weight loss journey.',
        'user' => 'HassanCramsie68',
        'blocks' => [
            ['user' => 'HassanCramsie68'],
        ],
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->expectsOutputToContain('Skipped existing page')
        ->assertSuccessful();

    expect($page->fresh())->not->toBeNull()
        ->and($page->fresh()->body)->toContain('Johto')
        ->and($page->revisions)->toHaveCount(0);

    Http::assertNotSent(fn (Request $request): bool => queryFromHttpRequest($request)['action'] === 'parse');
});

test('mediawiki import skips already imported pages', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    $page = WikiPage::factory()->create([
        'title' => 'Requirements',
        'slug' => 'requirements',
        'body' => 'Original local body.',
    ]);

    fakeMediawikiImport([
        'title' => 'Requirements',
        'wikitext' => "== Requirements ==\n\nThis should not overwrite the local page.\n",
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1])
        ->expectsOutputToContain('Skipped existing page')
        ->assertSuccessful();

    expect($page->fresh()->body)->toBe('Original local body.')
        ->and($page->revisions)->toHaveCount(0);

    Http::assertNotSent(fn (Request $request): bool => queryFromHttpRequest($request)['action'] === 'parse');
});

test('mediawiki import updates existing pages when forced', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    $page = WikiPage::factory()->create([
        'title' => 'Requirements',
        'slug' => 'requirements',
        'body' => 'Original local body.',
    ]);

    fakeMediawikiImport([
        'title' => 'Requirements',
        'wikitext' => "== Requirements ==\n\n'''Windows''' is required.\n",
    ]);

    $this->artisan('wiki:import-mediawiki', ['--limit' => 1, '--force' => true])
        ->assertSuccessful();

    expect($page->fresh()->body)->toContain('**Windows**')
        ->and($page->fresh()->revisions)->toHaveCount(1);
});

test('mediawiki spam-only option removes local spam without importing', function () {
    config(['services.wiki.api_url' => 'https://wiki.example.test/api.php']);

    $spam = WikiPage::factory()->create([
        'title' => 'Easy IRA Plan',
        'slug' => 'easy-ira-plan',
        'body' => 'A broad choice of gold and silver coins for IRAs.',
    ]);

    $keep = WikiPage::factory()->create([
        'title' => 'Azalea Town',
        'slug' => 'azalea-town',
        'body' => 'Azalea Town is a town in Johto. Visit the gym leader after speaking with Professor Elm.',
    ]);

    Http::preventStrayRequests();
    Http::fake();

    $this->artisan('wiki:import-mediawiki', ['--spam-only' => true])
        ->expectsOutputToContain('Skipped spam page')
        ->assertSuccessful();

    $this->assertSoftDeleted($spam);
    expect($keep->fresh())->not->toBeNull()
        ->and(WikiPage::query()->where('title', 'Requirements')->exists())->toBeFalse();

    Http::assertNothingSent();
});
