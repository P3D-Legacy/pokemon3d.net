<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Object storage parent disk
        |--------------------------------------------------------------------------
        |
        | Locally this is a plain local disk. On Laravel Cloud, create an object
        | storage resource whose disk name matches SKINS_OBJECT_DISK (default
        | "s3"). Cloud injects LARAVEL_CLOUD_DISK_CONFIG and overrides this entry.
        |
        */
        's3' => [
            'driver' => 'local',
            'root' => storage_path('app/object'),
            'url' => rtrim((string) env('APP_URL', 'http://localhost'), '/').'/storage/object',
            'visibility' => 'public',
            'throw' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Skin / player disks
        |--------------------------------------------------------------------------
        |
        | Scoped prefixes on the object storage parent disk. Paths stay skin/ and
        | player/ whether running locally or on Laravel Cloud.
        |
        */
        'skin' => [
            'driver' => 'scoped',
            'disk' => env('SKINS_OBJECT_DISK', 's3'),
            'prefix' => 'skin',
            'visibility' => 'public',
            'throw' => false,
        ],

        'player' => [
            'driver' => 'scoped',
            'disk' => env('SKINS_OBJECT_DISK', 's3'),
            'prefix' => 'player',
            'visibility' => 'public',
            'throw' => false,
        ],

    ],

];
