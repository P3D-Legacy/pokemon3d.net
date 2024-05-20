<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'lang_contribution_url' => env('LANG_CONTRIBUTION_URL', '/'),

    'consents' => [
        'tos.1' => 'Terms of Service &mdash; updated 2021-07-28',
        'email.newsletter' => 'E-mail: Receive an e-mail when we update the game or website',
        'email.notifications' => 'E-mail: Receive an e-mail if you have unread notifications',
    ],

    'required_consent' => 'tos.1',

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\NovaServiceProvider::class,
        App\Providers\Filament\AdminPanelProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
        App\Providers\JetstreamServiceProvider::class,
        App\Providers\HealthCheckServiceProvider::class,
    ])->toArray(),

];
