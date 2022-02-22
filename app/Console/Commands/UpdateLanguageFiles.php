<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
        $special_langs = ['cn', 'tw']; // these have a special long format
        $langs = Arr::flatten(config('language.allowed')); // get allowed languages
        $all_langs_codes = Arr::pluck(config('language.all'), 'long', 'short'); // get all languages short and long codes from config
        foreach ($langs as $lang) {
            if (in_array($lang, $special_langs)) {
                $this->info('Getting special name for ' . $lang . '...');
                Arr::pull($langs, array_search($lang, $langs)); // remove the lang from the array
                $lang_name = Str::replaceFirst('-', '_', $all_langs_codes[$lang]); // format the name correctly
                array_push($langs, $lang_name); // add the new name to the array
            }
        }
        Artisan::call('lang:add ' . implode(' ', $langs));
        Artisan::call('lang:update');
        Artisan::call('translatable:export ' . implode(',', $langs));
        $this->info('Done.');
    }
}
