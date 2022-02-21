<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class UpdateLanguageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'p3d:lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update language files';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Updating language files...');
        $langs = implode(',', array_values(config('language.allowed')));
        Artisan::call('translatable:export ' . $langs);
        $this->info('Done.');
    }
}
