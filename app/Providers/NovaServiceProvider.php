<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // TIMEZONE
        Nova::userTimezone(function ($request) {
            return $request->user() ? $request->user()->timezone : null;
        });

        // FOOTER
        Nova::footer(function ($request) {
            return Blade::render(
                '
            <p class="text-center">Powered by <a class="link-default" href="https://nova.laravel.com">Laravel Nova</a> · v{!! $version !!}</p>
            <p class="text-center">&copy; {!! $year !!} <a class="link-default" href="https://kilobyte.no">Kilobyte AS</a></p>
        ',
                [
                    'version' => Nova::version(),
                    'year' => date('Y'),
                ]
            );
        });

        // USER MENU
        Nova::userMenu(function ($request, $menu) {
            $menu
                ->prepend
                //
                ();

            return $menu;
        });

        // MAIN MENU
        Nova::mainMenu(function ($request, $menu) {
            $menu
                ->prepend
                //
                ();

            return $menu;
        });
    }

    /**
     * Register the Nova routes.
     */
    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewNova', function ($user) {
            return $user->hasRole('admin') || $user->hasRole('super-admin');
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     */
    protected function dashboards(): array
    {
        return [new \App\Nova\Dashboards\Main(), new \App\Nova\Dashboards\UserInsights()];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     */
    public function tools(): array
    {
        return [
            new \Bolechen\NovaActivitylog\NovaActivitylog(),
            new \Spatie\BackupTool\BackupTool(),
            \Vyuldashev\NovaPermission\NovaPermissionTool::make(),
        ];
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Nova::report(function ($exception) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }
        });
    }
}
