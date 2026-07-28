<?php

namespace App\Support;

class JsonTranslations
{
    /**
     * @return array<string, string>
     */
    public static function forLocale(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $fallbackLocale = config('app.fallback_locale', 'en');

        return [
            ...self::load($fallbackLocale),
            ...self::load($locale),
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function load(string $locale): array
    {
        foreach (self::candidatePaths($locale) as $path) {
            if (! is_file($path)) {
                continue;
            }

            $decoded = json_decode((string) file_get_contents($path), true);

            if (! is_array($decoded)) {
                return [];
            }

            /** @var array<string, string> $decoded */
            return $decoded;
        }

        return [];
    }

    /**
     * @return list<string>
     */
    private static function candidatePaths(string $locale): array
    {
        $underscore = str_replace('-', '_', $locale);

        return array_values(array_unique([
            lang_path("{$locale}.json"),
            lang_path("{$underscore}.json"),
        ]));
    }
}
