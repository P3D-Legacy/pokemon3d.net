<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToRetrieveMetadata;
use RuntimeException;
use Throwable;

class SkinStorage
{
    /**
     * @var array<string, bool>
     */
    private static array $loggedUnhealthyDisks = [];

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
        $contents = $file->get();

        if ($contents === false || $contents === '') {
            throw new RuntimeException('Failed to read uploaded skin file.');
        }

        self::putBytes('skin', self::libraryPath($uuid), $contents);
    }

    public static function storePlayer(UploadedFile $file, int|string $gamejoltId): void
    {
        $contents = $file->get();

        if ($contents === false || $contents === '') {
            throw new RuntimeException('Failed to read uploaded skin file.');
        }

        self::putBytes('player', self::playerPath($gamejoltId), $contents);
    }

    public static function putPlayer(int|string $gamejoltId, string $contents): void
    {
        self::putBytes('player', self::playerPath($gamejoltId), $contents);
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
        $base = self::libraryPublicUrlBase();

        if ($base === null) {
            return self::placeholderUrl();
        }

        $bust = $cacheBust ?? now()->timestamp;

        return $base.'/'.self::libraryPath($uuid).'?r='.$bust;
    }

    public static function urlPlayer(int|string $gamejoltId, ?int $cacheBust = null): string
    {
        $bust = $cacheBust ?? now()->timestamp;

        return asset('player/'.self::playerPath($gamejoltId)).'?r='.$bust;
    }

    public static function placeholderUrl(): string
    {
        return asset('img/noskin.png');
    }

    public static function copyLibraryToPlayer(string $uuid, int|string $gamejoltId): void
    {
        self::assertWritable('skin');
        self::assertWritable('player');

        try {
            $contents = Storage::disk('skin')->get(self::libraryPath($uuid));
        } catch (UnableToReadFile $exception) {
            throw new RuntimeException('Failed to read library skin for apply.', 0, $exception);
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed to read library skin for apply.', 0, $exception);
        }

        if (! is_string($contents) || $contents === '') {
            throw new RuntimeException('Library skin file is empty.');
        }

        self::putBytes('player', self::playerPath($gamejoltId), $contents);
    }

    public static function copyPlayerToLibrary(int|string $gamejoltId, string $uuid): void
    {
        self::assertWritable('skin');
        self::assertWritable('player');

        try {
            $contents = Storage::disk('player')->get(self::playerPath($gamejoltId));
        } catch (UnableToReadFile $exception) {
            throw new RuntimeException('Failed to read player skin for duplicate.', 0, $exception);
        } catch (Throwable $exception) {
            throw new RuntimeException('Failed to read player skin for duplicate.', 0, $exception);
        }

        if (! is_string($contents) || $contents === '') {
            throw new RuntimeException('Player skin file is empty.');
        }

        self::putBytes('skin', self::libraryPath($uuid), $contents);
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
        } catch (Throwable $exception) {
            self::logUnhealthyDisk('player', $exception);

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

    public static function hasExactDimensions(string $contents): bool
    {
        $info = @getimagesizefromstring($contents);

        if ($info === false) {
            return false;
        }

        $width = (int) config('skins.width');
        $height = (int) config('skins.height');

        return (int) $info[0] === $width && (int) $info[1] === $height;
    }

    private static function putBytes(string $disk, string $path, string $contents): void
    {
        self::assertWritable($disk);

        try {
            $stored = Storage::disk($disk)->put($path, $contents);
        } catch (Throwable $exception) {
            throw new RuntimeException("Failed to store skin on disk [{$disk}].", 0, $exception);
        }

        if ($stored === false) {
            throw new RuntimeException("Failed to store skin on disk [{$disk}].");
        }
    }

    private static function safeExists(string $disk, string $path): bool
    {
        if (! self::diskIsReady($disk)) {
            return false;
        }

        try {
            return Storage::disk($disk)->exists($path);
        } catch (UnableToCheckExistence|Throwable $exception) {
            self::logUnhealthyDisk($disk, $exception);

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
        } catch (UnableToRetrieveMetadata|UnableToCheckExistence|Throwable $exception) {
            self::logUnhealthyDisk($disk, $exception);

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
            } catch (Throwable $exception) {
                self::logUnhealthyDisk($disk, $exception);
            }
        }
    }

    private static function libraryPublicUrlBase(): ?string
    {
        $config = config('filesystems.disks.skin', []);

        if (($config['driver'] ?? null) === 'scoped') {
            $parent = (string) ($config['disk'] ?? '');
            $prefix = trim((string) ($config['prefix'] ?? ''), '/');
            $parentUrl = config("filesystems.disks.{$parent}.url");

            if (! is_string($parentUrl) || $parentUrl === '') {
                return null;
            }

            $base = rtrim($parentUrl, '/');

            return $prefix !== '' ? $base.'/'.$prefix : $base;
        }

        $url = $config['url'] ?? null;

        if (! is_string($url) || $url === '') {
            return null;
        }

        return rtrim($url, '/');
    }

    private static function diskIsReady(string $disk): bool
    {
        $config = config("filesystems.disks.{$disk}");

        if (! is_array($config) || $disk === '') {
            return false;
        }

        $driver = $config['driver'] ?? null;

        if ($driver === 'scoped') {
            $parent = (string) ($config['disk'] ?? '');

            return self::diskIsReady($parent) && self::libraryPublicUrlBase() !== null;
        }

        if ($driver === 's3') {
            return filled($config['bucket'] ?? null) && filled($config['url'] ?? null);
        }

        if ($driver === 'local') {
            $root = $config['root'] ?? null;

            return is_string($root) && $root !== '';
        }

        return filled($driver);
    }

    private static function assertWritable(string $disk): void
    {
        if ($disk === 'skin') {
            if (self::libraryPublicUrlBase() === null || ! self::parentObjectDiskIsConfigured()) {
                throw new RuntimeException('Filesystem disk [skin] is not configured.');
            }

            return;
        }

        if ($disk === 'player') {
            $root = config('filesystems.disks.player.root');

            if (! is_string($root) || $root === '') {
                throw new RuntimeException('Filesystem disk [player] is not configured.');
            }

            if (! is_dir($root) && ! mkdir($root, 0755, true) && ! is_dir($root)) {
                throw new RuntimeException('Filesystem disk [player] is not writable.');
            }

            if (! is_writable($root)) {
                throw new RuntimeException('Filesystem disk [player] is not writable.');
            }

            return;
        }

        if (! self::diskIsReady($disk)) {
            throw new RuntimeException("Filesystem disk [{$disk}] is not configured.");
        }
    }

    private static function parentObjectDiskIsConfigured(): bool
    {
        $config = config('filesystems.disks.skin', []);

        if (($config['driver'] ?? null) !== 'scoped') {
            return self::diskIsReady('skin');
        }

        $parent = (string) ($config['disk'] ?? '');
        $parentConfig = config("filesystems.disks.{$parent}", []);

        if (! is_array($parentConfig)) {
            return false;
        }

        $driver = $parentConfig['driver'] ?? null;

        if ($driver === 's3') {
            return filled($parentConfig['bucket'] ?? null);
        }

        if ($driver === 'local') {
            return filled($parentConfig['root'] ?? null);
        }

        return filled($driver);
    }

    private static function logUnhealthyDisk(string $disk, Throwable $exception): void
    {
        if (isset(self::$loggedUnhealthyDisks[$disk])) {
            return;
        }

        self::$loggedUnhealthyDisks[$disk] = true;

        Log::warning("Skin storage disk [{$disk}] check failed.", [
            'disk' => $disk,
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
        ]);
    }
}
