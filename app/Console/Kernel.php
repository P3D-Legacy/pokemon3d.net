<?php

namespace App\Console;

use App\Console\Commands\DiscordRoleSync;
use App\Console\Commands\DiscordUserRoleSync;
use App\Console\Commands\NotifyGameUpdate;
use App\Console\Commands\PingAllServers;
use App\Console\Commands\SkinUserUpdate;
use App\Console\Commands\SyncGameSave;
use App\Console\Commands\SyncGameSaveGamejoltAccountTrophies;
use App\Console\Commands\SyncGameVersion;
use App\Console\Commands\UpdateGamejoltAccountTrophies;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Activitylog\CleanActivitylogCommand;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Often commands
        $schedule->command(PingAllServers::class)->hourly();
        $schedule->command(UpdateGamejoltAccountTrophies::class)->hourly();
        $schedule->command(SkinUserUpdate::class)->hourlyAt(10);
        // Daily commands
        $schedule->command(DiscordRoleSync::class)->dailyAt('12:00');
        $schedule->command(DiscordUserRoleSync::class)->dailyAt('12:10');
        // Nightly commands
        $schedule->command(SyncGameVersion::class)->dailyAt('00:00');
        $schedule->command(NotifyGameUpdate::class)->dailyAt('00:30');
        $schedule->command(CleanActivitylogCommand::class)->dailyAt('01:00');
//        $schedule->command(SyncGameSave::class, ['gamejolt_user_id' => 'all'])->dailyAt('02:00');
//        $schedule->command(SyncGameSaveGamejoltAccountTrophies::class, ['gamejolt_user_id' => 'all'])->dailyAt('02:15');
        $schedule->command(SyncGameSave::class, ['gamejolt_user_id' => 'all'])->hourly();
        $schedule->command(SyncGameSaveGamejoltAccountTrophies::class, ['gamejolt_user_id' => 'all'])->hourlyAt(5);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
