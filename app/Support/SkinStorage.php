<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use RuntimeException;
use Throwable;

class SkinStorage
{
    public static function libraryPath(string $uuid): string
    {
        return "{$uuid}.png";
    }

    public static function playerPath(int|string $gamejoltId): string
    {
        return "{$gamejoltId}.png";
    }

    public static function storeLibrary(UploadedFile $file, string $uuid): void
    {
        self::ensureDiskReady('skin');

        $path = self::libraryPath($uuid);
        $contents = $file->get();

        if ($contents === false || $contents === '') {
            throw new RuntimeException('Failed to read uploaded skin file.');
        }

        try {
            $stored = Storage::disk('skin')->put($path, $contents);
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed to store library skin.', 0, $exception);
        }

        if ($stored === false) {
            throw new RuntimeException('Failed to store library skin.');
        }
    }

    public static function storePlayer(UploadedFile $file, int|string $gamejoltId): void
    {
        self::ensureDiskReady('player');

        $path = self::playerPath($gamejoltId);
        $contents = $file->get();

        if ($contents === false || $contents === '') {
            throw new RuntimeException('Failed to read uploaded skin file.');
        }

        try {
            $stored = Storage::disk('player')->put($path, $contents);
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed to store player skin.', 0, $exception);
        }

        if ($stored === false) {
            throw new RuntimeException('Failed to store player skin.');
        }
    }

    public static function putPlayer(int|string $gamejoltId, string $contents): void
    {
        self::ensureDiskReady('player');

        Storage::disk('player')->put(self::playerPath($gamejoltId), $contents);
    }

    public static function existsLibrary(string $uuid): bool
    {
        return self::safeExists('skin', self::libraryPath($uuid));
    }

    public static function existsPlayer(int|string $gamejoltId): bool
    {
        return self::safeExists('player', self::playerPath($gamejoltId));
    }

    public static function sizeLibrary(string $uuid): ?int
    {
        return self::safeSize('skin', self::libraryPath($uuid));
    }

    public static function sizePlayer(int|string $gamejoltId): ?int
    {
        return self::safeSize('player', self::playerPath($gamejoltId));
    }

    public static function deleteLibrary(string $uuid): void
    {
        self::safeDelete('skin', self::libraryPath($uuid));
    }

    public static function deletePlayer(int|string $gamejoltId): void
    {
        self::safeDelete('player', self::playerPath($gamejoltId));
    }

    public static function urlLibrary(string $uuid, ?int $cacheBust = null): string
    {
        $bust = $cacheBust ?? now()->timestamp;

        return self::publicUrl('skin', self::libraryPath($uuid)).'?r='.$bust;
    }

    public static function urlPlayer(int|string $gamejoltId, ?int $cacheBust = null): string
    {
        $bust = $cacheBust ?? now()->timestamp;

        return self::publicUrl('player', self::playerPath($gamejoltId)).'?r='.$bust;
    }

    public static function placeholderUrl(): string
    {
        return asset('img/noskin.png');
    }

    public static function copyLibraryToPlayer(string $uuid, int|string $gamejoltId): void
    {
        self::ensureDiskReady('skin');
        self::ensureDiskReady('player');

        try {
            $contents = Storage::disk('skin')->get(self::libraryPath($uuid));
        } catch (UnableToReadFile $exception) {
            throw $exception;
        }

        Storage::disk('player')->put(self::playerPath($gamejoltId), $contents);
    }

    public static function copyPlayerToLibrary(int|string $gamejoltId, string $uuid): void
    {
        self::ensureDiskReady('skin');
        self::ensureDiskReady('player');

        try {
            $contents = Storage::disk('player')->get(self::playerPath($gamejoltId));
        } catch (UnableToReadFile $exception) {
            throw $exception;
        }

        Storage::disk('skin')->put(self::libraryPath($uuid), $contents);
    }

    /**
     * @return list<string>
     */
    public static function playerFiles(): array
    {
        if (! self::diskIsReady('player')) {
            return [];
        }

        try {
            return collect(Storage::disk('player')->files())
                ->filter(fn (string $item): bool => str_ends_with(strtolower($item), '.png'))
                ->values()
                ->all();
        } catch (Throwable) {
            return [];
        }
    }

    /**
     * @return list<string>
     */
    public static function imageValidationRules(bool $required = true): array
    {
        $width = (int) config('skins.width');
        $height = (int) config('skins.height');

        $rules = [
            'image',
            'max:2000',
            'mimes:png',
            "dimensions:width={$width},height={$height}",
        ];

        if ($required) {
            array_unshift($rules, 'required');
        }

        return $rules;
    }

    public static function isValidPng(string $contents): bool
    {
        if (strlen($contents) < 8) {
            return false;
        }

        return str_starts_with($contents, "\x89PNG\r\n\x1a\n");
    }

    private static function safeExists(string $disk, string $path): bool
    {
        if (! self::diskIsReady($disk)) {
            return false;
        }

        try {
            return Storage::disk($disk)->exists($path);
        } catch (UnableToCheckExistence|Throwable) {
            // Flysystem raises UnableToCheckFileExistence on S3/R2 auth/network
            // errors; Laravel's exists() does not honour disks.*.throw = false.
            return false;
        }
    }

    private static function safeSize(string $disk, string $path): ?int
    {
        if (! self::diskIsReady($disk)) {
            return null;
        }

        try {
            return (int) Storage::disk($disk)->size($path);
        } catch (UnableToRetrieveMetadata|UnableToCheckExistence|Throwable) {
            return null;
        }
    }

    private static function safeDelete(string $disk, string $path): void
    {
        if (! self::diskIsReady($disk)) {
            return;
        }

        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
            }
        } catch (UnableToCheckExistence|Throwable) {
            try {
                Storage::disk($disk)->delete($path);
            } catch (Throwable) {
                // Best-effort cleanup when existence checks are unavailable.
            }
        }
    }

    /**
     * Build a public object URL without constructing the S3 adapter when possible.
     * Storage::disk()->url() requires a non-null bucket and crashes otherwise.
     */
    private static function publicUrl(string $disk, string $path): string
    {
        $config = config("filesystems.disks.{$disk}", []);

        if (($config['driver'] ?? null) === 'scoped') {
            $parent = (string) ($config['disk'] ?? '');
            $prefix = trim((string) ($config['prefix'] ?? ''), '/');
            $parentConfig = config("filesystems.disks.{$parent}", []);
            $base = $parentConfig['url'] ?? null;
            $relative = $prefix !== '' ? "{$prefix}/{$path}" : $path;

            if (is_string($base) && $base !== '') {
                return rtrim($base, '/').'/'.$relative;
            }

            if (self::diskIsReady($disk)) {
                try {
                    return Storage::disk($disk)->url($path);
                } catch (Throwable) {
                    // Fall through to legacy public paths.
                }
            }

            return match ($disk) {
                'player' => asset('player/'.$path),
                default => asset('img/skin/'.$path),
            };
        }

        $base = $config['url'] ?? null;
        $root = trim((string) ($config['root'] ?? ''), '/');
        $relative = $root !== '' ? "{$root}/{$path}" : $path;

        if (is_string($base) && $base !== '') {
            return rtrim($base, '/').'/'.$relative;
        }

        if (self::diskIsReady($disk)) {
            try {
                return Storage::disk($disk)->url($path);
            } catch (Throwable) {
                // Fall through to legacy public paths.
            }
        }

        return match ($disk) {
            'player' => asset('player/'.$path),
            default => asset('img/skin/'.$path),
        };
    }

    private static function diskIsReady(string $disk): bool
    {
        $config = config("filesystems.disks.{$disk}");

        if (! is_array($config) || $disk === '') {
            return false;
        }

        $driver = $config['driver'] ?? null;

        if ($driver === 'scoped') {
            return self::diskIsReady((string) ($config['disk'] ?? ''));
        }

        if ($driver === 's3') {
            return filled($config['bucket'] ?? null);
        }

        return filled($driver);
    }

    private static function ensureDiskReady(string $disk): void
    {
        if (self::diskIsReady($disk)) {
            return;
        }

        throw new RuntimeException("Filesystem disk [{$disk}] is not configured.");
    }
}
