<?php

namespace App\Spotlight;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\WikiPage;
use LivewireUI\Spotlight\Spotlight;
use LivewireUI\Spotlight\SpotlightCommand;
use LivewireUI\Spotlight\SpotlightCommandDependencies;
use LivewireUI\Spotlight\SpotlightCommandDependency;
use LivewireUI\Spotlight\SpotlightSearchResult;

class WikiSearch extends SpotlightCommand
{
    protected string $name = 'Search Wiki';

    protected string $description = 'Search the wiki for a specific title.';

    protected array $synonyms = ['wiki', 'wikipedia', 'wiki search', 'documentation', 'docs', 'docs search'];

    public function dependencies(): ?SpotlightCommandDependencies
    {
        return SpotlightCommandDependencies::collection()->add(
            SpotlightCommandDependency::make('wiki')
                ->setPlaceholder('For which title do you want to search?')
        );
    }

    public function searchWiki($query)
    {
        $term = trim((string) $query);

        return collect()
            ->concat(WikiPage::query()->published()->search($term !== '' ? $term : null)->limit(8)->get())
            ->concat(PokemonSpecies::query()->search($term !== '' ? $term : null)->limit(8)->get())
            ->concat(PokemonMove::query()->search($term !== '' ? $term : null)->limit(4)->get())
            ->concat(PokemonItem::query()->search($term !== '' ? $term : null)->limit(4)->get())
            ->concat(PokemonAbility::query()->search($term !== '' ? $term : null)->limit(4)->get())
            ->unique(fn ($entry) => $entry::class.'-'.$entry->getKey())
            ->map(fn ($entry) => new SpotlightSearchResult(
                $entry->slug,
                $entry->wikiTitle(),
                sprintf('Show details for %s', $entry->wikiTitle())
            ));
    }

    public function execute(Spotlight $spotlight, $wiki)
    {
        $spotlight->redirect(route('wiki.show', $wiki));
    }

    public function shouldBeShown(): bool
    {
        return true;
    }
}
