<?php

namespace App\Support;

class CloudObjectStorage
{
    public static function configure(): void
    {
        $diskName = self::injectedDiskName();

        if ($diskName === null) {
            return;
        }

        $url = config("filesystems.disks.{$diskName}.url");

        if (! filled($url) && filled(config('filesystems.object_public_url'))) {
            config([
                "filesystems.disks.{$diskName}.url" => config('filesystems.object_public_url'),
            ]);
        }

        config([
            'filesystems.disks.skin.disk' => $diskName,
            'filesystems.disks.resource.disk' => $diskName,
        ]);
    }

    public static function injectedDiskName(): ?string
    {
        $raw = $_SERVER['LARAVEL_CLOUD_DISK_CONFIG'] ?? null;

        if (! is_string($raw) || $raw === '') {
            return null;
        }

        $disks = json_decode($raw, true);

        if (! is_array($disks) || $disks === []) {
            return null;
        }

        $preferred = config('filesystems.disks.skin.disk');

        foreach ($disks as $disk) {
            if (! is_array($disk) || ! isset($disk['disk'])) {
                continue;
            }

            if ($preferred !== null && $disk['disk'] === $preferred) {
                return (string) $disk['disk'];
            }
        }

        foreach ($disks as $disk) {
            if (is_array($disk) && ($disk['is_default'] ?? false) && isset($disk['disk'])) {
                return (string) $disk['disk'];
            }
        }

        $first = $disks[0] ?? null;

        if (! is_array($first) || ! isset($first['disk'])) {
            return null;
        }

        return (string) $first['disk'];
    }
}
