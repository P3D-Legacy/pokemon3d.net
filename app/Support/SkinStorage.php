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
        // Always build the public disk URL. Existence checks can throw on S3/R2
        // (Laravel exists() ignores throw => false) even when the object is public.
        $url = Storage::disk('skin')->url(self::libraryPath($uuid));
        $bust = $cacheBust ?? now()->timestamp;

        return "{$url}?r={$bust}";
    }

    public static function urlPlayer(int|string $gamejoltId, ?int $cacheBust = null): string
    {
        $url = Storage::disk('player')->url(self::playerPath($gamejoltId));
        $bust = $cacheBust ?? now()->timestamp;

        return "{$url}?r={$bust}";
    }

    public static function placeholderUrl(): string
    {
        return asset('img/noskin.png');
    }

    public static function copyLibraryToPlayer(string $uuid, int|string $gamejoltId): void
    {
        try {
            $contents = Storage::disk('skin')->get(self::libraryPath($uuid));
        } catch (UnableToReadFile $exception) {
            throw $exception;
        }

        Storage::disk('player')->put(self::playerPath($gamejoltId), $contents);
    }

    public static function copyPlayerToLibrary(int|string $gamejoltId, string $uuid): void
    {
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
        try {
            return (int) Storage::disk($disk)->size($path);
        } catch (UnableToRetrieveMetadata|UnableToCheckExistence|Throwable) {
            return null;
        }
    }

    private static function safeDelete(string $disk, string $path): void
    {
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
}
