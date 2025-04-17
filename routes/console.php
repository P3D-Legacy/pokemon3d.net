<?php

use App\Console\Commands\CleanUpActivity;
use App\Console\Commands\DiscordRoleSync;
use App\Console\Commands\DiscordUserRoleSync;
use App\Console\Commands\NotifyGameUpdate;
use App\Console\Commands\PingAllServers;
use App\Console\Commands\SkinUserUpdate;
use App\Console\Commands\SyncGameVersion;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Propaganistas\LaravelDisposableEmail\Console\UpdateDisposableDomainsCommand;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Often commands
Schedule::command(PingAllServers::class)->hourly();
Schedule::command(SkinUserUpdate::class)->hourlyAt(10);
// Daily commands
Schedule::command(DiscordRoleSync::class)->dailyAt('12:00');
Schedule::command(DiscordUserRoleSync::class)->dailyAt('12:10');
// Nightly commands
Schedule::command(SyncGameVersion::class)->dailyAt('00:00');
Schedule::command(NotifyGameUpdate::class)->dailyAt('00:30');
// Weekly commands
Schedule::command(UpdateDisposableDomainsCommand::class)->weekly();
Schedule::command(CleanUpActivity::class)->weekly();
