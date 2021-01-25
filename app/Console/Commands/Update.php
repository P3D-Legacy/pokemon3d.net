<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sp3d:update';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $rev = exec('git rev-parse --short HEAD');
        $branch = exec('git describe --tags --abbrev=0');
        $ver = $branch.' ('.$rev.')';

        $this->info('Migrating...');
        Artisan::call('migrate --force');
        $this->info('Updating version...');
        if (setting('APP_VERSION') != $ver) {
            $this->info('Current version: '.setting('APP_VERSION'));
            setting()->set('APP_VERSION', $ver);
            $this->info('Updated version to: '.setting('APP_VERSION'));
            setting()->save();
        }
        $this->info('Done.');
    }
}
