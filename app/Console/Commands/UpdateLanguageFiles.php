<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

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
     */
    public function handle(): int
    {
        $this->info('Updating language files...');
        $special_languages = ['cn', 'tw', 'pt-BR']; // these have a special long format
        $languages = Arr::flatten(config('language.allowed')); // get allowed languages
        $all_lang_codes = Arr::pluck(config('language.all'), 'long', 'short'); // get all languages short and long codes from config
        foreach ($languages as $lang) {
            if (in_array($lang, $special_languages)) {
                $this->info('Getting special name for '.$lang.'...');
                Arr::pull($languages, array_search($lang, $languages)); // remove the lang from the array
                $lang_name = Str::replaceFirst('-', '_', $all_lang_codes[$lang]); // format the name correctly
                $languages[] = $lang_name; // add the new name to the array
                $this->info('Special name for '.$lang.' is '.$lang_name);
            }
        }
        Artisan::call('lang:add '.implode(' ', $languages)); // add the languages
        Artisan::call('translatable:export '.implode(',', $languages)); // export the translations
        $lang_path = base_path('lang'); // get the lang path
        foreach ($special_languages as $lang) {
            $long_lang_name = Str::replaceFirst('-', '_', language()->getLongCode($lang)); // get the long name
            $current_lang_long_path = $lang_path.'/'.$long_lang_name; // get the current long lang path
            $current_lang_short_path = $lang_path.'/'.$lang; // get the current short lang path
            try {
                if (file_exists($current_lang_long_path.'.json')) {
                    $this->info('Renaming file for '.$lang.' ('.$long_lang_name.')...');
                    copy($current_lang_long_path.'.json', $current_lang_short_path.'.json'); // rename the long path to the short path
                }
            } catch (\Exception $e) {
                $this->error('Error renaming files for '.$lang.' ('.$long_lang_name.'): '.$e->getMessage());
            }
            try {
                if (is_dir($current_lang_long_path)) {
                    $this->info('Starting renaming folder for '.$lang.' ('.$long_lang_name.')...');
                    foreach (glob($current_lang_long_path.'/*') as $file) {
                        copy($file, $current_lang_short_path.'/'.basename($file)); // rename the long path to the short path
                    }
                }
            } catch (\Exception $e) {
                $this->error('Error renaming folder for '.$lang.' ('.$long_lang_name.'): '.$e->getMessage());
            }
        }
        $this->info('Language files updated!');

        return Command::SUCCESS;
    }
}
