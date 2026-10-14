<?php

namespace App\Console\Commands;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\User;
use App\Models\WikiPage;
use App\Models\WikiRedirect;
use App\Support\MediaWikiSpamDetector;
use App\Support\MediaWikiToMarkdown;
use App\Support\WikiSlug;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ImportWikiFromMediawiki extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wiki:import-mediawiki {--limit= : Max new pages to import, or max pages to scan with --spam-only} {--user= : User ID for imported pages} {--force : Update pages that were already imported} {--spam-only : Soft delete spam pages without importing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import the latest MediaWiki prose pages, images, and redirects';

    public function handle(): int
    {
        if ($this->option('spam-only')) {
            return $this->pruneLocalSpam();
        }

        $apiUrl = config('services.wiki.api_url');

        if (! $apiUrl) {
            $this->error('Wiki API URL is not set.');

            return self::FAILURE;
        }

        $user = $this->importUser();
        $detector = new MediaWikiSpamDetector($this->blockedUsers($apiUrl));
        $imported = 0;
        $skippedExisting = 0;
        $skippedSpam = 0;
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;
        $continue = null;

        do {
            $query = [
                'action' => 'query',
                'format' => 'json',
                'list' => 'allpages',
                'apnamespace' => 0,
                'aplimit' => 50,
                'apfilterredir' => 'nonredirects',
            ];

            if ($continue) {
                $query['apcontinue'] = $continue;
            }

            $response = Http::timeout(20)->acceptJson()->get($apiUrl, $query);

            if ($response->failed()) {
                $this->error('Failed to list MediaWiki pages.');

                return self::FAILURE;
            }

            $pages = $response->json('query.allpages') ?? [];

            foreach ($pages as $page) {
                $status = $this->importPage((string) ($page['title'] ?? ''), $user, $apiUrl, $detector);

                if ($status === 'imported') {
                    $imported++;
                } elseif ($status === 'skipped') {
                    $skippedExisting++;
                } else {
                    $skippedSpam++;
                }

                if ($limit !== null && $imported >= $limit) {
                    $this->summariseImport($imported, $skippedExisting, $skippedSpam);

                    return self::SUCCESS;
                }
            }

            $continue = $response->json('continue.apcontinue');
        } while ($continue);

        $this->importRedirects($apiUrl, $limit);
        $this->summariseImport($imported, $skippedExisting, $skippedSpam);

        return self::SUCCESS;
    }

    protected function pruneLocalSpam(): int
    {
        $detector = new MediaWikiSpamDetector;
        $checked = 0;
        $removed = 0;
        $limit = $this->option('limit') !== null ? (int) $this->option('limit') : null;

        WikiPage::query()
            ->orderBy('id')
            ->chunkById(100, function (Collection $pages) use ($detector, $limit, &$checked, &$removed): bool {
                /** @var Collection<int, WikiPage> $pages */
                foreach ($pages as $page) {
                    if ($limit !== null && $checked >= $limit) {
                        return false;
                    }

                    $checked++;
                    $reason = $detector->reason($page->title, $page->body);

                    if (! $reason) {
                        continue;
                    }

                    $this->rejectSpam($page->title, $reason, $detector);
                    $removed++;
                }

                return $limit === null || $checked < $limit;
            });

        $this->info("Checked {$checked} pages.");

        if ($removed > 0) {
            $this->warn("Removed {$removed} spam pages.");
        }

        return self::SUCCESS;
    }

    /**
     * @return 'imported'|'skipped'|'spam'
     */
    protected function importPage(string $title, User $user, string $apiUrl, MediaWikiSpamDetector $detector): string
    {
        $page = $this->existingPage($title);

        if ($page && ! $this->option('force')) {
            if ($detector->isSpam($page->title, $page->body)) {
                $this->rejectSpam($title, $detector->reason($page->title, $page->body) ?? 'spam', $detector);

                return 'spam';
            }

            $this->line("Skipped existing page {$title}");

            return 'skipped';
        }

        $response = Http::timeout(20)->acceptJson()->get($apiUrl, [
            'action' => 'parse',
            'format' => 'json',
            'page' => $title,
            'prop' => 'wikitext|images',
        ]);

        if ($response->failed()) {
            $this->warn("Could not parse {$title}.");

            return 'skipped';
        }

        $wikitext = $this->wikitext($response);
        $target = MediaWikiToMarkdown::isRedirect($wikitext);

        if ($target) {
            $this->storeRedirect($title, $target);

            return 'imported';
        }

        $editor = $this->pageEditor($title, $apiUrl);
        $spamReason = $detector->reason($title, $wikitext, $editor);

        if ($spamReason) {
            $this->rejectSpam($title, $spamReason, $detector);

            return 'spam';
        }

        if ($this->isEncyclopediaTitle($title) || MediaWikiToMarkdown::isMostlyTemplate($wikitext)) {
            $this->redirectTitleToEncyclopedia($title);

            return 'imported';
        }

        $body = MediaWikiToMarkdown::convert($wikitext);
        $page ??= $this->existingPage($title);

        if (! $page) {
            $page = WikiPage::create([
                'title' => $title,
                'slug' => WikiSlug::unique($title),
                'body' => $body !== '' ? $body : $title,
                'published' => true,
                'user_id' => $user->id,
            ]);
            $page->recordRevision($user, __('Imported from MediaWiki'));
        } else {
            $page->update([
                'title' => $title,
                'body' => $body !== '' ? $body : $page->body,
                'published' => true,
            ]);
            $page->recordRevision($user, __('Imported from MediaWiki'));
        }

        foreach ($response->json('parse.images') ?? [] as $image) {
            $this->importImage($page, (string) $image, $apiUrl);
        }

        $this->info("Imported page {$title}");

        return 'imported';
    }

    protected function existingPage(string $title): ?WikiPage
    {
        $slug = WikiSlug::fromTitle($title);

        return WikiPage::query()->where('slug', $slug)->orWhere('title', $title)->first();
    }

    protected function rejectSpam(string $title, string $reason, MediaWikiSpamDetector $detector): void
    {
        $this->warn("Skipped spam page {$title} ({$reason}).");

        $page = WikiPage::query()->where('slug', WikiSlug::fromTitle($title))->orWhere('title', $title)->first();

        if (! $page || $detector->looksLikeGameContent($page->title, $page->body)) {
            return;
        }

        $page->delete();
    }

    /**
     * @return list<string>
     */
    protected function blockedUsers(string $apiUrl): array
    {
        $users = [];
        $continue = null;

        do {
            $query = [
                'action' => 'query',
                'format' => 'json',
                'list' => 'blocks',
                'bklimit' => 500,
                'bkprop' => 'user',
            ];

            if ($continue) {
                $query['bkcontinue'] = $continue;
            }

            $response = Http::timeout(20)->connectTimeout(5)->acceptJson()->get($apiUrl, $query);

            if ($response->failed()) {
                $this->warn('Could not load MediaWiki blocked users.');

                break;
            }

            foreach ($response->json('query.blocks') ?? [] as $block) {
                if (is_string($block['user'] ?? null) && $block['user'] !== '') {
                    $users[] = $block['user'];
                }
            }

            $continue = $response->json('continue.bkcontinue');
        } while ($continue);

        return array_values(array_unique($users));
    }

    protected function pageEditor(string $title, string $apiUrl): ?string
    {
        $response = Http::timeout(20)->connectTimeout(5)->acceptJson()->get($apiUrl, [
            'action' => 'query',
            'format' => 'json',
            'prop' => 'revisions',
            'rvprop' => 'user',
            'rvlimit' => 1,
            'titles' => $title,
        ]);

        if ($response->failed()) {
            return null;
        }

        $pages = $response->json('query.pages') ?? [];
        $user = data_get(array_values($pages)[0] ?? [], 'revisions.0.user');

        return is_string($user) && $user !== '' ? $user : null;
    }

    protected function summariseImport(int $imported, int $skippedExisting, int $skippedSpam): void
    {
        $this->info("Imported {$imported} pages.");

        if ($skippedExisting > 0) {
            $this->info("Skipped {$skippedExisting} existing pages.");
        }

        if ($skippedSpam > 0) {
            $this->warn("Skipped {$skippedSpam} spam pages.");
        }
    }

    protected function importImage(WikiPage $page, string $filename, string $apiUrl): void
    {
        $response = Http::timeout(20)->acceptJson()->get($apiUrl, [
            'action' => 'query',
            'format' => 'json',
            'titles' => 'File:'.$filename,
            'prop' => 'imageinfo',
            'iiprop' => 'url',
        ]);

        $pages = $response->json('query.pages') ?? [];
        $url = collect($pages)->flatten(1)->get('imageinfo.0.url')
            ?? data_get(array_values($pages)[0] ?? [], 'imageinfo.0.url');

        if (! is_string($url) || $url === '') {
            return;
        }

        try {
            $page->addMediaFromUrl($url)->toMediaCollection('images');
        } catch (\Throwable $exception) {
            $this->warn("Could not import image {$filename}: {$exception->getMessage()}");
        }
    }

    protected function importRedirects(string $apiUrl, ?int $limit): void
    {
        $response = Http::timeout(20)->acceptJson()->get($apiUrl, [
            'action' => 'query',
            'format' => 'json',
            'list' => 'allpages',
            'apnamespace' => 0,
            'apfilterredir' => 'redirects',
            'aplimit' => $limit ?? 50,
        ]);

        foreach ($response->json('query.allpages') ?? [] as $page) {
            $title = (string) ($page['title'] ?? '');
            $parse = Http::timeout(20)->acceptJson()->get($apiUrl, [
                'action' => 'parse',
                'format' => 'json',
                'page' => $title,
                'prop' => 'wikitext',
            ]);
            $target = MediaWikiToMarkdown::isRedirect($this->wikitext($parse));

            if ($target) {
                $this->storeRedirect($title, $target);
            }
        }
    }

    protected function storeRedirect(string $fromTitle, string $toTitle): void
    {
        $target = $this->findByTitle($toTitle);

        if (! $target) {
            return;
        }

        WikiRedirect::query()->updateOrCreate(
            ['from_slug' => WikiSlug::fromTitle($fromTitle)],
            [
                'redirectable_type' => $target::class,
                'redirectable_id' => $target->getKey(),
            ]
        );
    }

    protected function redirectTitleToEncyclopedia(string $title): void
    {
        $clean = Str::after($title, '/');
        $target = $this->findByTitle($clean) ?? $this->findByTitle($title);

        if (! $target) {
            return;
        }

        WikiRedirect::query()->updateOrCreate(
            ['from_slug' => WikiSlug::fromTitle($title)],
            [
                'redirectable_type' => $target::class,
                'redirectable_id' => $target->getKey(),
            ]
        );
    }

    protected function findByTitle(string $title): ?Model
    {
        $slug = WikiSlug::fromTitle($title);

        return WikiPage::query()->where('slug', $slug)->orWhere('title', $title)->first()
            ?? PokemonSpecies::query()->where('slug', $slug)->orWhere('name', $title)->first()
            ?? PokemonMove::query()->where('slug', $slug)->orWhere('name', $title)->first()
            ?? PokemonItem::query()->where('slug', $slug)->orWhere('name', $title)->first()
            ?? PokemonAbility::query()->where('slug', $slug)->orWhere('name', $title)->first();
    }

    protected function isEncyclopediaTitle(string $title): bool
    {
        return str_starts_with($title, 'Pokémon Move/')
            || str_starts_with($title, 'Pokemon Move/');
    }

    protected function wikitext(Response $response): string
    {
        $wikitext = $response->json('parse.wikitext');

        if (is_array($wikitext)) {
            return (string) ($wikitext['*'] ?? '');
        }

        return (string) $wikitext;
    }

    protected function importUser(): User
    {
        $userId = $this->option('user');

        if ($userId) {
            return User::query()->findOrFail($userId);
        }

        return User::query()->firstOrCreate(
            ['email' => 'wiki-import@pokemon3d.net'],
            [
                'name' => 'Wiki Import',
                'username' => 'wiki-import',
                'password' => Str::password(32),
            ]
        );
    }
}
