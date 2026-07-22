<?php

use App\Console\Commands\CleanUpActivity;
use App\Console\Commands\DiscordRoleSync;
use App\Console\Commands\DiscordUserRoleSync;
use App\Console\Commands\NotifyGameUpdate;
use App\Console\Commands\PingAllServers;
use App\Console\Commands\SkinUserUpdate;
use App\Console\Commands\SyncGameVersion;
use App\Console\Commands\SyncPokedexFromGame;
use Illuminate\Queue\Console\PruneBatchesCommand;
use Illuminate\Queue\Console\PruneFailedJobsCommand;
use Illuminate\Support\Facades\Schedule;

// Often commands
Schedule::command(PingAllServers::class)->hourly();
Schedule::command(SkinUserUpdate::class)->hourlyAt(10);
// Daily commands
Schedule::command(PruneBatchesCommand::class)->daily();
Schedule::command(DiscordRoleSync::class)->dailyAt('12:00');
Schedule::command(DiscordUserRoleSync::class)->dailyAt('12:10');
Schedule::command(SyncGameVersion::class)->dailyAt('00:00');
Schedule::command(NotifyGameUpdate::class)->dailyAt('00:30');
Schedule::command('disposable:update')->daily();
// Weekly commands
Schedule::command(PruneFailedJobsCommand::class)->weekly();
Schedule::command(CleanUpActivity::class)->weekly();
Schedule::command(SyncPokedexFromGame::class)->weekly();
