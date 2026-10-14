<?php

namespace App\Support;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\WikiPage;
use App\Models\WikiRedirect;
use Illuminate\Database\Eloquent\Model;

class WikiEntryResolver
{
    /**
     * @return array{kind: string, entry: Model}|null
     */
    public static function resolve(string $slug, bool $includeUnpublished = false): ?array
    {
        $redirect = WikiRedirect::query()
            ->where('from_slug', $slug)
            ->with('redirectable')
            ->first();

        if ($redirect?->redirectable) {
            return self::wrap($redirect->redirectable);
        }

        foreach (WikiSlug::models() as $class) {
            $query = $class::query()->where('slug', $slug);

            if ($class === WikiPage::class && ! $includeUnpublished) {
                $query->published();
            }

            $entry = $query->first();

            if ($entry) {
                return self::wrap($entry);
            }
        }

        return null;
    }

    public static function urlForTitle(string $title): string
    {
        $slug = WikiSlug::fromTitle($title);
        $match = self::resolve($slug, true);

        if ($match) {
            return route('wiki.show', $match['entry']->slug);
        }

        $page = WikiPage::query()->where('title', $title)->first();
        if ($page) {
            return route('wiki.show', $page->slug);
        }

        foreach ([PokemonSpecies::class, PokemonMove::class, PokemonItem::class, PokemonAbility::class] as $class) {
            $entry = $class::query()->where('name', $title)->first();
            if ($entry) {
                return route('wiki.show', $entry->slug);
            }
        }

        return route('wiki.show', $slug);
    }

    /**
     * @return array{kind: string, entry: Model}
     */
    public static function wrap(Model $entry): array
    {
        return [
            'kind' => match ($entry::class) {
                PokemonSpecies::class => 'pokemon',
                PokemonMove::class => 'move',
                PokemonItem::class => 'item',
                PokemonAbility::class => 'ability',
                default => 'page',
            },
            'entry' => $entry,
        ];
    }
}
