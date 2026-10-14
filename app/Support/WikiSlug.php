<?php

namespace App\Support;

use App\Models\PokemonAbility;
use App\Models\PokemonItem;
use App\Models\PokemonMove;
use App\Models\PokemonSpecies;
use App\Models\WikiPage;
use App\Models\WikiRedirect;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WikiSlug
{
    public static function fromTitle(string $title): string
    {
        $slug = Str::slug($title);

        return $slug !== '' ? $slug : 'page';
    }

    public static function exists(string $slug, ?Model $ignore = null): bool
    {
        return collect(self::models())
            ->contains(function (string $class) use ($slug, $ignore): bool {
                $query = $class::query()->where('slug', $slug);

                if ($ignore instanceof $class) {
                    $query->whereKeyNot($ignore->getKey());
                }

                return $query->exists();
            }) || WikiRedirect::query()->where('from_slug', $slug)->exists();
    }

    public static function unique(string $title, ?Model $ignore = null): string
    {
        $base = self::fromTitle($title);
        $slug = $base;
        $suffix = 2;

        while (self::exists($slug, $ignore)) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * @return list<class-string<Model>>
     */
    public static function models(): array
    {
        return [
            WikiPage::class,
            PokemonSpecies::class,
            PokemonMove::class,
            PokemonItem::class,
            PokemonAbility::class,
        ];
    }
}
