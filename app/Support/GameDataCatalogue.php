<?php

namespace App\Support;

use Throwable;

class GameDataCatalogue
{
    /**
     * @var array<string, array<string, ?string>>
     */
    private static array $names = [];

    public static function flush(): void
    {
        self::$names = [];
    }

    public static function loadedListCount(): int
    {
        return count(self::$names);
    }

    public function pokemonName(string $id): string
    {
        return $this->name('pokemon', $id, '???');
    }

    public function itemName(string $id): string
    {
        if ($id === '' || $id === '0') {
            return __('None');
        }

        return $this->name('items', $id, "Item #{$id}");
    }

    public function abilityName(string $id): string
    {
        if ($id === '') {
            return __('None');
        }

        return $this->name('abilities', $id, '???');
    }

    public function natureName(string $id): string
    {
        if ($id === '') {
            return __('None');
        }

        return $this->name('natures', $id, "Nature #{$id}");
    }

    private function name(string $filePrefix, string $id, string $fallback): string
    {
        $names = $this->names($filePrefix);

        if (! array_key_exists($id, $names) || $names[$id] === null) {
            return $fallback;
        }

        return $names[$id];
    }

    /**
     * @return array<string, ?string>
     */
    private function names(string $filePrefix): array
    {
        $key = $filePrefix.'|'.app()->getLocale();

        if (isset(self::$names[$key])) {
            return self::$names[$key];
        }

        $path = lang_path($filePrefix.'_'.app()->getLocale().'.json');

        if (! is_file($path)) {
            $path = lang_path($filePrefix.'_en.json');
        }

        if (! is_file($path)) {
            return self::$names[$key] = [];
        }

        try {
            $decoded = json_decode((string) file_get_contents($path), true);
        } catch (Throwable $exception) {
            report($exception);

            return self::$names[$key] = [];
        }

        if (! is_array($decoded)) {
            return self::$names[$key] = [];
        }

        $names = [];

        foreach ($decoded as $row) {
            if (! is_array($row) || ! isset($row['id'])) {
                continue;
            }

            $rowId = (string) $row['id'];

            if (array_key_exists($rowId, $names)) {
                continue;
            }

            $names[$rowId] = array_key_exists('name', $row) ? (string) $row['name'] : null;
        }

        return self::$names[$key] = $names;
    }
}
