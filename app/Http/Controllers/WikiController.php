<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWikiPageRequest;
use App\Http\Requests\UpdateWikiPageRequest;
use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\WikiPage;
use App\Models\WikiRevision;
use App\Support\WikiDiff;
use App\Support\WikiEntryResolver;
use App\Support\WikiPresenter;
use App\Support\WikiSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class WikiController extends Controller
{
    public function index(Request $request): Response
    {
        $term = $request->string('q')->toString();
        $tag = $request->string('tag')->toString();
        $kind = $request->string('kind')->toString();
        $includeUnpublished = $request->user()?->can('wiki.show') ?? false;

        $entries = $this->searchEntries($term, $tag, $kind, $includeUnpublished)
            ->sortByDesc(fn (Model $entry): string => (string) $entry->updated_at)
            ->values();

        $page = max(1, (int) $request->integer('page', 1));
        $perPage = 15;
        $slice = $entries->forPage($page, $perPage)->values();

        $paginator = new LengthAwarePaginator(
            $slice->map(fn (Model $entry): array => WikiPresenter::card($entry))->all(),
            $entries->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return Inertia::render('wiki/index', [
            'entries' => $paginator,
            'filters' => [
                'q' => $term,
                'tag' => $tag,
                'kind' => $kind,
            ],
            'tags' => WikiPage::query()
                ->with('tags')
                ->get()
                ->pluck('tags')
                ->flatten()
                ->pluck('name')
                ->unique()
                ->values()
                ->all(),
            'canCreate' => $request->user()?->can('create', WikiPage::class) ?? false,
            'legacyWikiUrl' => config('services.wiki.base_url'),
            'copy' => $this->indexCopy(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', WikiPage::class);

        return Inertia::render('wiki/create', [
            'copy' => $this->formCopy(__('Create wiki page')),
        ]);
    }

    public function store(StoreWikiPageRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $page = WikiPage::create([
            'title' => $validated['title'],
            'slug' => WikiSlug::unique($validated['title']),
            'body' => $validated['body'],
            'published' => $request->boolean('published', true),
            'user_id' => $request->user()->id,
        ]);

        $this->syncTags($page, $validated['tags'] ?? null);
        $page->recordRevision($request->user(), $validated['summary'] ?? __('Created page'));

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item created successfully.', ['item' => __('Wiki page')]));

        return redirect()->route('wiki.show', $page->slug);
    }

    public function show(Request $request, string $slug): Response|RedirectResponse
    {
        $includeUnpublished = $request->user()?->can('wiki.show') ?? false;
        $resolved = WikiEntryResolver::resolve($slug, $includeUnpublished);

        if (! $resolved) {
            abort(404);
        }

        /** @var Model $entry */
        $entry = $resolved['entry'];

        if ($entry->slug !== $slug) {
            return redirect()->route('wiki.show', $entry->slug);
        }

        if ($entry instanceof WikiPage) {
            $this->authorize('view', $entry);
        }

        $component = match ($resolved['kind']) {
            'pokemon' => 'wiki/pokemon',
            'move' => 'wiki/move',
            'item' => 'wiki/item',
            'ability' => 'wiki/ability',
            default => 'wiki/show',
        };

        $payload = match ($resolved['kind']) {
            'pokemon' => WikiPresenter::pokemon($entry, $request->user()),
            'move' => WikiPresenter::move($entry, $request->user()),
            'item' => WikiPresenter::item($entry, $request->user()),
            'ability' => WikiPresenter::ability($entry, $request->user()),
            default => WikiPresenter::page($entry, $request->user()),
        };

        return Inertia::render($component, [
            'entry' => $payload,
            'copy' => $this->showCopy(),
        ]);
    }

    public function edit(Request $request, string $slug): Response
    {
        $resolved = $this->resolveForEditor($request, $slug);

        if ($resolved['kind'] === 'page') {
            return Inertia::render('wiki/edit', [
                'entry' => WikiPresenter::form($resolved['entry']),
                'copy' => $this->formCopy(__('Edit wiki page')),
            ]);
        }

        return Inertia::render('wiki/edit-encyclopedia', [
            'entry' => match ($resolved['kind']) {
                'pokemon' => WikiPresenter::pokemon($resolved['entry'], $request->user()),
                'move' => WikiPresenter::move($resolved['entry'], $request->user()),
                'item' => WikiPresenter::item($resolved['entry'], $request->user()),
                default => WikiPresenter::ability($resolved['entry'], $request->user()),
            },
            'copy' => $this->formCopy(__('Edit wiki page')),
        ]);
    }

    public function update(UpdateWikiPageRequest $request, string $slug): RedirectResponse
    {
        $resolved = $this->resolveForEditor($request, $slug);
        $entry = $resolved['entry'];
        $validated = $request->validated();

        if ($entry instanceof WikiPage) {
            $entry->update([
                'title' => $validated['title'] ?? $entry->title,
                'slug' => isset($validated['title'])
                    ? WikiSlug::unique($validated['title'], $entry)
                    : $entry->slug,
                'body' => $validated['body'] ?? $entry->body,
                'published' => $request->has('published')
                    ? $request->boolean('published')
                    : $entry->published,
            ]);
            $this->syncTags($entry, $validated['tags'] ?? null);
        } else {
            $entry->update(collect($validated)->only($entry->getFillable())->all());
        }

        $entry->recordRevision($request->user(), $validated['summary'] ?? __('Updated page'));

        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', __(':item updated successfully.', ['item' => __('Wiki page')]));

        return redirect()->route('wiki.show', $entry->fresh()->slug);
    }

    public function history(Request $request, string $slug): Response
    {
        $resolved = WikiEntryResolver::resolve($slug, $request->user()?->can('wiki.show') ?? false);

        if (! $resolved) {
            abort(404);
        }

        $revisions = $resolved['entry']
            ->revisions()
            ->with('user')
            ->get()
            ->map(fn (WikiRevision $revision): array => WikiPresenter::revision($revision));

        return Inertia::render('wiki/history', [
            'entry' => [
                'kind' => $resolved['kind'],
                'title' => $resolved['entry']->wikiTitle(),
                'slug' => $resolved['entry']->slug,
            ],
            'revisions' => $revisions,
            'copy' => [
                'wiki' => __('Wiki'),
                'history' => __('Revision history'),
                'empty' => __('There is nothing to show'),
                'summary' => __('Summary'),
            ],
        ]);
    }

    public function revision(Request $request, string $slug, WikiRevision $revision): Response
    {
        $resolved = WikiEntryResolver::resolve($slug, $request->user()?->can('wiki.show') ?? false);

        if (! $resolved || $revision->revisionable_id !== $resolved['entry']->getKey()) {
            abort(404);
        }

        $previous = WikiRevision::query()
            ->where('revisionable_type', $revision->revisionable_type)
            ->where('revisionable_id', $revision->revisionable_id)
            ->where('id', '<', $revision->id)
            ->orderByDesc('id')
            ->first();

        return Inertia::render('wiki/revision', [
            'entry' => [
                'kind' => $resolved['kind'],
                'title' => $resolved['entry']->wikiTitle(),
                'slug' => $resolved['entry']->slug,
            ],
            'revision' => WikiPresenter::revision($revision),
            'diff' => WikiDiff::lines($previous?->body, $revision->body),
            'copy' => [
                'wiki' => __('Wiki'),
                'history' => __('Revision history'),
                'revision' => __('Revision'),
            ],
        ]);
    }

    /**
     * @return array{kind: string, entry: Model}
     */
    protected function resolveForEditor(Request $request, string $slug): array
    {
        $resolved = WikiEntryResolver::resolve($slug, true);

        if (! $resolved) {
            abort(404);
        }

        if ($resolved['entry'] instanceof WikiPage) {
            $this->authorize('update', $resolved['entry']);
        } else {
            $this->authorize('editEncyclopedia', WikiPage::class);
        }

        return $resolved;
    }

    protected function syncTags(WikiPage $page, ?string $tags): void
    {
        $names = collect(explode(',', (string) $tags))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->values()
            ->all();

        $page->syncTagsWithType($names, 'wiki');
    }

    /**
     * @return Collection<int, Model>
     */
    protected function searchEntries(string $term, string $tag, string $kind, bool $includeUnpublished): Collection
    {
        $pages = WikiPage::query()
            ->with(['user', 'tags'])
            ->when(! $includeUnpublished, fn ($query) => $query->published())
            ->when($tag !== '', fn ($query) => $query->withAnyTags([$tag], 'wiki'))
            ->search($term !== '' ? $term : null)
            ->get();

        $species = PokemonSpecies::query()->search($term !== '' ? $term : null)->get();
        $moves = PokemonMove::query()->search($term !== '' ? $term : null)->get();
        $items = PokemonItem::query()->search($term !== '' ? $term : null)->get();
        $abilities = PokemonAbility::query()->search($term !== '' ? $term : null)->get();

        return collect()
            ->when($kind === '' || $kind === 'page', fn (Collection $entries) => $entries->concat($pages))
            ->when($kind === '' || $kind === 'pokemon', fn (Collection $entries) => $entries->concat($species))
            ->when($kind === '' || $kind === 'move', fn (Collection $entries) => $entries->concat($moves))
            ->when($kind === '' || $kind === 'item', fn (Collection $entries) => $entries->concat($items))
            ->when($kind === '' || $kind === 'ability', fn (Collection $entries) => $entries->concat($abilities));
    }

    /**
     * @return array<string, string>
     */
    protected function indexCopy(): array
    {
        return [
            'title' => __('Wiki'),
            'subtitle' => __('Guides, places, and the Pokémon 3D encyclopedia.'),
            'search' => __('Search'),
            'searchPlaceholder' => __('Search the wiki'),
            'create' => __('Create wiki page'),
            'nothingToShow' => __('There is nothing to show'),
            'allKinds' => __('All'),
            'pages' => __('Pages'),
            'pokemon' => __('Pokémon'),
            'moves' => __('Moves'),
            'items' => __('Items'),
            'abilities' => __('Abilities'),
            'legacyNotice' => __('The previous MediaWiki is still available while we finish moving every article.'),
            'updated' => __('Updated'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function showCopy(): array
    {
        return [
            'wiki' => __('Wiki'),
            'edit' => __('Edit'),
            'history' => __('Revision history'),
            'updated' => __('Updated'),
            'unpublished' => __('Unpublished'),
            'notes' => __('Notes'),
            'stats' => __('Stats'),
            'abilities' => __('Abilities'),
            'moves' => __('Moves'),
            'locations' => __('Locations'),
            'effect' => __('Effect'),
            'type' => __('Type'),
            'category' => __('Category'),
            'power' => __('Power'),
            'accuracy' => __('Accuracy'),
            'pp' => __('PP'),
            'buy' => __('Buy'),
            'sell' => __('Sell'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function formCopy(string $title): array
    {
        return [
            'title' => $title,
            'wiki' => __('Wiki'),
            'name' => __('Title'),
            'body' => __('Body'),
            'notes' => __('Notes'),
            'tags' => __('Tags'),
            'tagsHelp' => __('Separate tags with commas.'),
            'published' => __('Published'),
            'summary' => __('Summary'),
            'submit' => __('Save'),
            'cancel' => __('Cancel'),
            'markdownHelp' => __('Write in Markdown. Use [[Page Title]] to link to other wiki pages.'),
        ];
    }
}
