<?php

declare(strict_types=1);
use AliBayat\LaravelCategorizable\Category;

/**
 * Laravel Categorizable Package by Ali Bayat.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Eloquent Models
    |--------------------------------------------------------------------------
    */

    'models' => [
        /*
        |--------------------------------------------------------------------------
        | Package's Category Model
        |--------------------------------------------------------------------------
        */

        'category' => Category::class,
    ],
];
