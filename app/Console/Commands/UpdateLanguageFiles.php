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
    public function handle(): int
    {
        $this->info('Updating language files...');
        $special_languages = ['cn', 'tw']; // these have a special long format
        $languages = Arr::flatten(config('language.allowed')); // get allowed languages
        $all_lang_codes = Arr::pluck(config('language.all'), 'long', 'short'); // get all languages short and long codes from config
        foreach ($languages as $lang) {
            if (in_array($lang, $special_languages)) {
                $this->info('Getting special name for ' . $lang . '...');
                Arr::pull($languages, array_search($lang, $languages)); // remove the lang from the array
                $lang_name = Str::replaceFirst('-', '_', $all_lang_codes[$lang]); // format the name correctly
                $languages[] = $lang_name; // add the new name to the array
                $this->info('Special name for ' . $lang . ' is ' . $lang_name);
            }
        }
        Artisan::call('lang:add ' . implode(' ', $languages)); // add the languages
        Artisan::call('translatable:export ' . implode(',', $languages)); // export the translations
        $this->info('Language files updated!');
        return 0;
    }
}
