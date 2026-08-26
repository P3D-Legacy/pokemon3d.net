<?php

namespace App\Support;

use App\Models\User;

class EmblemCatalogue
{
    /**
     * @return list<string>
     */
    public static function alwaysUnlocked(): array
    {
        return array_values(config('emblems.always_unlocked', ['trainer']));
    }

    /**
     * @return array<int, string>
     */
    public static function trophyMap(): array
    {
        return config('emblems.trophy_map', []);
    }

    public static function slugForTrophyId(int $trophyId): ?string
    {
        $slug = self::trophyMap()[$trophyId] ?? null;

        if (! is_string($slug) || $slug === '' || $slug === 'fail') {
            return null;
        }

        return $slug;
    }

    public static function hasAsset(string $slug): bool
    {
        return is_file(public_path('img/emblems/'.$slug.'.png'));
    }

    public static function url(?string $slug): ?string
    {
        if ($slug === null || $slug === '' || ! self::hasAsset($slug)) {
            return null;
        }

        return asset('img/emblems/'.$slug.'.png');
    }

    /**
     * @return list<string>
     */
    public static function knownSlugs(): array
    {
        return collect(self::alwaysUnlocked())
            ->merge(array_values(self::trophyMap()))
            ->unique()
            ->filter(fn (string $slug): bool => self::hasAsset($slug))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    public static function unlockedFor(User $user): array
    {
        $user->loadMissing(['gamesave', 'gamejolt.trophies']);

        $unlocked = collect(self::alwaysUnlocked());

        if ($user->gamesave) {
            $unlocked = $unlocked->merge($user->gamesave->getAchievements());
        }

        if ($user->gamejolt) {
            $unlocked = $unlocked->merge(
                $user->gamejolt->trophies
                    ->where('achieved', true)
                    ->map(fn ($trophy): ?string => self::slugForTrophyId((int) $trophy->id))
                    ->filter()
            );
        }

        return $unlocked
            ->filter(fn (mixed $slug): bool => is_string($slug) && $slug !== '')
            ->map(fn (string $slug): string => strtolower(trim($slug)))
            ->unique()
            ->filter(fn (string $slug): bool => self::hasAsset($slug))
            ->sort()
            ->values()
            ->all();
    }

    public static function isUnlockedFor(User $user, string $slug): bool
    {
        return in_array(strtolower(trim($slug)), self::unlockedFor($user), true);
    }

    public static function effectiveSlug(User $user): ?string
    {
        $slug = $user->profile_background ?? $user->gamejolt_emblem;

        if ($slug === null || $slug === '' || ! self::hasAsset($slug)) {
            return null;
        }

        return $slug;
    }

    public static function coverImageUrl(User $user): ?string
    {
        return self::url(self::effectiveSlug($user));
    }

    /**
     * @return list<array{slug: string, label: string, image_url: string|null, unlocked: bool}>
     */
    public static function pickerOptionsFor(User $user): array
    {
        $unlocked = collect(self::unlockedFor($user))->flip();

        return collect(self::knownSlugs())
            ->map(fn (string $slug): array => [
                'slug' => $slug,
                'label' => self::label($slug),
                'image_url' => self::url($slug),
                'unlocked' => $unlocked->has($slug),
            ])
            ->values()
            ->all();
    }

    public static function label(string $slug): string
    {
        return str($slug)->headline()->toString();
    }

    /**
     * Normalise a DataStore emblem value into a catalogue slug.
     */
    public static function normaliseSlug(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $slug = strtolower(trim(str_replace(['\\"', '"'], '', $value)));

        if ($slug === '' || ! self::hasAsset($slug)) {
            return null;
        }

        return $slug;
    }
}
