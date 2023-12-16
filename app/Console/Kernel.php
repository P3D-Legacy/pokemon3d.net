<?php

namespace App\Console;

use App\Console\Commands\DiscordRoleSync;
use App\Console\Commands\DiscordUserRoleSync;
use App\Console\Commands\NotifyGameUpdate;
use App\Console\Commands\PingAllServers;
use App\Console\Commands\SkinUserUpdate;
use App\Console\Commands\SyncGameVersion;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Spatie\Health\Commands\RunHealthChecksCommand;
use Spatie\Health\Commands\ScheduleCheckHeartbeatCommand;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Often commands
        $schedule->command(RunHealthChecksCommand::class)->everyMinute();
        $schedule->command(ScheduleCheckHeartbeatCommand::class)->everyMinute();
        $schedule->command(PingAllServers::class)->hourly();
        $schedule->command(SkinUserUpdate::class)->hourlyAt(10);
        // Daily commands
        $schedule->command(DiscordRoleSync::class)->dailyAt('12:00');
        $schedule->command(DiscordUserRoleSync::class)->dailyAt('12:10');
        $schedule->command('model:prune', ['--model' => MonitoredScheduledTaskLogItem::class])->daily();
        // Nightly commands
        $schedule->command(SyncGameVersion::class)->dailyAt('00:00');
        $schedule->command(NotifyGameUpdate::class)->dailyAt('00:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
