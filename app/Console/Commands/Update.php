<?php

namespace App\Console\Commands;

use anlutro\LaravelSettings\Facade as Setting;
use App\Models\DiscordBotSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p3d:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Essential stuff needed for a update.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $rev = exec('git rev-parse --short HEAD');
        $branch = exec('git describe --tags --abbrev=0');
        $ver = $branch.' ('.$rev.')';

        $this->info('Migrating...');
        Artisan::call('migrate --force');
        $this->info('Updating version...');
        if (Setting::get('APP_VERSION') != $ver) {
            $this->info('Current version: '.Setting::get('APP_VERSION'));
            Setting::set('APP_VERSION', $ver);
            $this->info('Updated version to: '.Setting::get('APP_VERSION'));
            Setting::save();
        }
        $this->info('Seeding permissions...');
        Artisan::call('db:seed --class=PermissionSeeder --force');
        $this->info('Seeding ban reasons...');
        Artisan::call('db:seed --class=BanReasonSeeder --force');
        $this->info('Giving SA...');
        Artisan::call('p3d:givesa');
        $this->info('Running SkinUserUpdate command...');
        Artisan::call('p3d:skinuserupdate');
        $this->info('Running storage:link command...');
        Artisan::call('storage:link');
        $this->info('Generating API Docs...');
        Artisan::call('scribe:generate');
        $this->info('Getting Github release...');
        Artisan::call('github:syncrelease');
        $this->info('Getting Discord roles...');
        Artisan::call('discord:syncroles');
        $this->info('Getting Discord user roles...');
        Artisan::call('discord:syncuserroles');
        $this->info('Saving DiscordBotSetting...');
        if (DiscordBotSetting::first() === null) {
            DiscordBotSetting::create([
                'hide_events' => '{}',
            ]);
        }
        $this->info('Publishing nova assets...');
        Artisan::call('nova:publish');
        $this->info('Clear views...');
        Artisan::call('view:clear');
        $this->info('Sync schedule monitor...');
        $this->call('schedule-monitor:sync');
        $this->info('Done.');

        return 0;
    }
}
