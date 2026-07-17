<?php

use Illuminate\Support\Facades\Schema;

it('creates cache tables when they are missing', function () {
    Schema::dropIfExists('cache');
    Schema::dropIfExists('cache_locks');

    $migration = require database_path('migrations/2021_07_11_090231_create_cache_table.php');
    $migration->up();

    expect(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('cache_locks'))->toBeTrue();
});

it('skips creating cache tables when they already exist', function () {
    expect(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('cache_locks'))->toBeTrue();

    $migration = require database_path('migrations/2021_07_11_090231_create_cache_table.php');
    $migration->up();

    expect(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('cache_locks'))->toBeTrue();
});

it('creates only the missing cache table when the other already exists', function () {
    Schema::dropIfExists('cache_locks');

    expect(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('cache_locks'))->toBeFalse();

    $migration = require database_path('migrations/2021_07_11_090231_create_cache_table.php');
    $migration->up();

    expect(Schema::hasTable('cache'))->toBeTrue()
        ->and(Schema::hasTable('cache_locks'))->toBeTrue();
});
