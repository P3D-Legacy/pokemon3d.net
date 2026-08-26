<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MapNameCatalogue
{
    /**
     * @var list<string>
     */
    private const WORLDMAP_FILES = [
        'johto.dat',
        'kanto.dat',
        'sevii islands.dat',
        'battle frontier.dat',
    ];

    public static function name(string $mapPath): string
    {
        $normalized = self::normalizePath($mapPath);

        if ($normalized === '') {
            return 'Unknown location';
        }

        $catalogue = self::catalogue();

        return $catalogue[$normalized]
            ?? $catalogue[self::stripRegionPrefix($normalized)]
            ?? $catalogue[basename(str_replace('\\', '/', $normalized))]
            ?? self::humanizePath($normalized);
    }

    /**
     * @return array<string, string>
     */
    public static function catalogue(): array
    {
        return Cache::remember('p3d.worldmap.place_names', now()->addDay(), function (): array {
            $map = [];

            foreach (self::WORLDMAP_FILES as $file) {
                try {
                    $response = Http::timeout(5)->get(
                        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Data/Scripts/worldmap/'.$file
                    );

                    if (! $response->successful()) {
                        continue;
                    }

                    $map = [...$map, ...self::parseWorldmap((string) $response->body())];
                } catch (Throwable $e) {
                    Log::warning('Failed to load P3D worldmap place names.', [
                        'file' => $file,
                        'message' => $e->getMessage(),
                    ]);
                }
            }

            return $map;
        });
    }

    /**
     * @return array<string, string>
     */
    public static function parseWorldmap(string $raw): array
    {
        $map = [];

        foreach (preg_split('/\r\n|\n|\r/', $raw) ?: [] as $line) {
            if ($line === '') {
                continue;
            }

            if (! preg_match('/\{"Name"\[([^\]]+)\]\}/i', $line, $nameMatch)) {
                continue;
            }

            if (! preg_match('/\{"MapFiles?"\[([^\]]*)\]\}/i', $line, $filesMatch)) {
                continue;
            }

            $name = trim($nameMatch[1]);

            if ($name === '') {
                continue;
            }

            foreach (array_filter(array_map('trim', explode(',', $filesMatch[1]))) as $file) {
                $normalized = self::normalizePath($file);

                if ($normalized === '') {
                    continue;
                }

                $map[$normalized] ??= $name;
                $basename = basename(str_replace('\\', '/', $normalized));
                $map[$basename] ??= $name;
            }
        }

        return $map;
    }

    public static function normalizePath(string $path): string
    {
        return ltrim(strtolower(str_replace('/', '\\', trim($path))), '\\');
    }

    public static function stripRegionPrefix(string $normalizedPath): string
    {
        return (string) preg_replace('/^(johto|kanto|sevii islands|battle frontier)\\\\/i', '', $normalizedPath);
    }

    public static function humanizePath(string $normalizedPath): string
    {
        $parts = array_values(array_filter(explode('\\', $normalizedPath)));

        if ($parts === []) {
            return 'Unknown location';
        }

        $leaf = (string) preg_replace('/\.dat$/i', '', (string) end($parts));

        if (in_array($leaf, ['main', '0', '1', '2'], true) && count($parts) >= 2) {
            $leaf = (string) preg_replace('/\.dat$/i', '', $parts[count($parts) - 2]);
        }

        $leaf = str_replace(['_', '-'], ' ', $leaf);
        $leaf = (string) preg_replace('/([a-z])(\d)/i', '$1 $2', $leaf);

        return Str::title($leaf);
    }
}
