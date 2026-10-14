<?php

namespace App\Support;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\User;
use App\Models\WikiPage;
use App\Models\WikiRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WikiPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function card(Model $entry): array
    {
        $wrapped = WikiEntryResolver::wrap($entry);

        return [
            'kind' => $wrapped['kind'],
            'title' => $entry->wikiTitle(),
            'slug' => $entry->slug,
            'excerpt' => self::excerpt($entry),
            'url' => route('wiki.show', $entry->slug),
            'updated_at' => $entry->updated_at?->diffForHumans(),
            'tags' => $entry instanceof WikiPage
                ? $entry->tags->pluck('name')->all()
                : [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function page(WikiPage $page, ?User $viewer = null): array
    {
        return [
            'kind' => 'page',
            'uuid' => $page->uuid,
            'title' => $page->title,
            'slug' => $page->slug,
            'body' => $page->body,
            'body_html' => self::html($page->body),
            'published' => $page->published,
            'updated_at' => $page->updated_at?->isoFormat('LLLL'),
            'author' => [
                'username' => $page->user?->username,
                'profile_photo_url' => $page->user?->profile_photo_url,
            ],
            'tags' => $page->tags->pluck('name')->all(),
            'images' => $page->getMedia('images')->map(fn ($media): array => [
                'url' => $media->getUrl(),
                'name' => $media->file_name,
            ])->all(),
            'permissions' => [
                'can_update' => $viewer?->can('update', $page) ?? false,
                'can_delete' => $viewer?->can('delete', $page) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function pokemon(PokemonSpecies $species, ?User $viewer = null): array
    {
        return [
            'kind' => 'pokemon',
            'title' => $species->name,
            'slug' => $species->slug,
            'game_id' => $species->game_id,
            'type_1' => $species->type_1,
            'type_2' => $species->type_2,
            'hp' => $species->hp,
            'attack' => $species->attack,
            'defense' => $species->defense,
            'special_attack' => $species->special_attack,
            'special_defense' => $species->special_defense,
            'speed' => $species->speed,
            'ability_1' => $species->ability_1,
            'ability_2' => $species->ability_2,
            'hidden_ability' => $species->hidden_ability,
            'height' => $species->height,
            'weight' => $species->weight,
            'species' => $species->species,
            'pokedex_entry' => $species->pokedex_entry,
            'moves' => $species->moves ?? [],
            'egg_moves' => $species->egg_moves ?? [],
            'machines' => $species->machines ?? [],
            'evolution' => $species->evolution,
            'notes' => $species->notes,
            'notes_html' => self::html((string) $species->notes),
            'permissions' => [
                'can_update' => $viewer?->can('wiki.update') ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function move(PokemonMove $move, ?User $viewer = null): array
    {
        return [
            'kind' => 'move',
            'title' => $move->name,
            'slug' => $move->slug,
            'game_id' => $move->game_id,
            'type' => $move->type,
            'category' => $move->category,
            'power' => $move->power,
            'accuracy' => $move->accuracy,
            'pp' => $move->pp,
            'effect' => $move->effect,
            'notes' => $move->notes,
            'notes_html' => self::html((string) $move->notes),
            'permissions' => [
                'can_update' => $viewer?->can('wiki.update') ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function item(PokemonItem $item, ?User $viewer = null): array
    {
        return [
            'kind' => 'item',
            'title' => $item->name,
            'slug' => $item->slug,
            'game_id' => $item->game_id,
            'category' => $item->category,
            'buy_price' => $item->buy_price,
            'sell_price' => $item->sell_price,
            'effect' => $item->effect,
            'notes' => $item->notes,
            'notes_html' => self::html((string) $item->notes),
            'permissions' => [
                'can_update' => $viewer?->can('wiki.update') ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function ability(PokemonAbility $ability, ?User $viewer = null): array
    {
        return [
            'kind' => 'ability',
            'title' => $ability->name,
            'slug' => $ability->slug,
            'game_id' => $ability->game_id,
            'effect' => $ability->effect,
            'notes' => $ability->notes,
            'notes_html' => self::html((string) $ability->notes),
            'permissions' => [
                'can_update' => $viewer?->can('wiki.update') ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function form(WikiPage $page): array
    {
        return [
            'kind' => 'page',
            'uuid' => $page->uuid,
            'title' => $page->title,
            'slug' => $page->slug,
            'body' => $page->body,
            'published' => $page->published,
            'tags' => $page->tags->pluck('name')->implode(', '),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function revision(WikiRevision $revision): array
    {
        return [
            'id' => $revision->id,
            'title' => $revision->title,
            'body' => $revision->body,
            'summary' => $revision->summary,
            'created_at' => $revision->created_at?->isoFormat('LLLL'),
            'created_for_humans' => $revision->created_at?->diffForHumans(),
            'author' => $revision->user?->username,
        ];
    }

    public static function html(?string $markdown): string
    {
        if (! filled($markdown)) {
            return '';
        }

        return (string) Str::of(WikiLinkRenderer::render($markdown))->markdown();
    }

    public static function excerpt(Model $entry): string
    {
        if ($entry instanceof WikiPage) {
            return $entry->excerpt ?: $entry->makeExcerpt();
        }

        if ($entry instanceof PokemonSpecies) {
            return (string) ($entry->pokedex_entry ?: $entry->species);
        }

        if (isset($entry->effect) && filled($entry->effect)) {
            return (string) $entry->effect;
        }

        return Str::limit(strip_tags(self::html((string) ($entry->notes ?? ''))), 175);
    }
}
