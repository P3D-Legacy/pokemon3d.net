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
        | Used for library skins. Locally this is a plain local disk. On Laravel
        | Cloud, create an object storage resource whose disk name matches
        | SKINS_OBJECT_DISK (default "s3"). Cloud injects LARAVEL_CLOUD_DISK_CONFIG
        | and overrides this entry.
        |
        */
        's3' => [
            'driver' => 'local',
            'root' => storage_path('app/object'),
            'url' => rtrim((string) env('APP_URL', 'http://localhost'), '/').'/storage/object',
            'visibility' => 'public',
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Library skins (object storage)
        |--------------------------------------------------------------------------
        |
        | Scoped prefix on the object storage parent disk. Paths stay skin/{uuid}.png
        | whether running locally or on Laravel Cloud.
        |
        */
        'skin' => [
            'driver' => 'scoped',
            'disk' => env('SKINS_OBJECT_DISK', 's3'),
            'prefix' => 'skin',
            'visibility' => 'public',
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Resource update files (object storage)
        |--------------------------------------------------------------------------
        |
        | Scoped prefix on the object storage parent disk. Media Library paths
        | stay resource/{mediaId}/{fileName} locally and on Laravel Cloud.
        |
        */
        'resource' => [
            'driver' => 'scoped',
            'disk' => env('RESOURCES_OBJECT_DISK', 's3'),
            'prefix' => 'resource',
            'throw' => true,
        ],

        /*
        |--------------------------------------------------------------------------
        | Player / in-game skins (local public)
        |--------------------------------------------------------------------------
        |
        | Classic game-facing path: public/player/{gamejoltId}.png served as
        | /player/{gamejoltId}.png on the application domain.
        |
        */
        'player' => [
            'driver' => 'local',
            'root' => public_path('player'),
            'url' => rtrim((string) env('APP_URL', 'http://localhost'), '/').'/player',
            'visibility' => 'public',
            'throw' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    */
    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('storage/object') => storage_path('app/object'),
    ],

];
