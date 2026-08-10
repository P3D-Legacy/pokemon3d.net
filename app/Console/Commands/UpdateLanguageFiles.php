<?php

namespace App\Console\Commands;

use FilesystemIterator;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class UpdateLanguageFiles extends Command
{
    /**
     * Locales that use a different on-disk name than the app language code.
     *
     * @var array<string, string>
     */
    private array $specialLocaleMap = [
        'cn' => 'zh_CN',
        'tw' => 'zh_TW',
        'pt-BR' => 'pt_BR',
        'en-GB' => 'en_GB',
    ];

    /**
     * Locales that Laravel Lang can publish, mapped from app language codes.
     * British English is intentionally omitted; the publisher has no en_GB locale.
     *
     * @var array<string, string>
     */
    private array $publisherLocaleMap = [
        'cn' => 'zh_CN',
        'tw' => 'zh_TW',
        'pt-BR' => 'pt_BR',
    ];

    protected $signature = 'p3d:lang';

    protected $description = 'Update language files';

    public function handle(): int
    {
        $this->info('Updating language files...');

        $allowed = array_values(array_unique(Arr::flatten(config('language.allowed'))));
        $langPath = lang_path();

        $publisherLocales = [];
        foreach ($allowed as $locale) {
            if ($locale === 'en-GB') {
                $this->info('Skipping en-GB for lang:add (not available in Laravel Lang).');

                continue;
            }

            $publisherLocales[] = $this->publisherLocaleMap[$locale] ?? $locale;
        }

        $publisherLocales = array_values(array_unique($publisherLocales));

        $this->info('Adding Laravel Lang localizations: '.implode(', ', $publisherLocales));
        Artisan::call('lang:add', ['locales' => $publisherLocales]);
        $this->output->write(Artisan::output());

        $this->refreshPersistentStrings();

        $exportLocales = [];
        foreach ($allowed as $locale) {
            $exportLocales[] = $this->specialLocaleMap[$locale] ?? $locale;
            $exportLocales[] = $locale;
        }

        $exportLocales = array_values(array_unique($exportLocales));

        $this->info('Exporting translatable strings: '.implode(', ', $exportLocales));
        Artisan::call('translatable:export', ['lang' => implode(',', $exportLocales)]);
        $this->output->write(Artisan::output());

        $frontendKeys = $this->extractFrontendTranslationKeys();
        $this->info('Merging '.count($frontendKeys).' frontend t() keys...');
        $this->mergeKeysIntoLocales($frontendKeys, $exportLocales);

        $this->syncEnglishKeysToLocales($exportLocales);
        $this->refreshPersistentStrings();

        foreach ($this->specialLocaleMap as $shortLocale => $longLocale) {
            $longJson = $langPath.'/'.$longLocale.'.json';
            $shortJson = $langPath.'/'.$shortLocale.'.json';

            if (File::exists($longJson)) {
                $this->info("Syncing {$longLocale}.json to {$shortLocale}.json...");
                File::copy($longJson, $shortJson);
            }

            $longDir = $langPath.'/'.$longLocale;
            $shortDir = $langPath.'/'.$shortLocale;

            if (File::isDirectory($longDir)) {
                $this->info("Syncing {$longLocale}/ to {$shortLocale}/...");
                File::ensureDirectoryExists($shortDir);

                foreach (File::files($longDir) as $file) {
                    File::copy($file->getPathname(), $shortDir.'/'.$file->getFilename());
                }
            }
        }

        if (File::isDirectory($langPath.'/en')) {
            $this->info('Syncing en/ PHP files to en_GB/ and en-GB/...');

            foreach (['en_GB', 'en-GB'] as $britishDir) {
                File::ensureDirectoryExists($langPath.'/'.$britishDir);

                foreach (File::files($langPath.'/en') as $file) {
                    File::copy($file->getPathname(), $langPath.'/'.$britishDir.'/'.$file->getFilename());
                }
            }
        }

        $this->info('Language files updated!');

        return self::SUCCESS;
    }

    private function refreshPersistentStrings(): void
    {
        $englishPath = lang_path('en.json');
        $english = $this->readJsonObject($englishPath);
        $keys = array_keys($english);
        sort($keys, SORT_STRING | SORT_FLAG_CASE);

        File::put(
            lang_path('persistent-strings.json'),
            json_encode($keys, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n"
        );

        $this->info('Refreshed persistent-strings.json ('.count($keys).' keys).');
    }

    /**
     * @return list<string>
     */
    private function extractFrontendTranslationKeys(): array
    {
        $keys = [];
        $directories = [resource_path('js'), resource_path('views')];
        $pattern = '/(?<![\w$])t\(\s*([\'"])((?:\\\\.|(?!\1).)*)\1/u';

        foreach ($directories as $directory) {
            if (! File::isDirectory($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $extension = strtolower($file->getExtension());
                if (! in_array($extension, ['js', 'jsx', 'ts', 'tsx', 'vue', 'php'], true)) {
                    continue;
                }

                $contents = (string) File::get($file->getPathname());

                if (preg_match_all($pattern, $contents, $matches) === false) {
                    continue;
                }

                foreach ($matches[2] as $key) {
                    $key = stripcslashes($key);

                    if ($key !== '') {
                        $keys[$key] = true;
                    }
                }
            }
        }

        $keys = array_keys($keys);
        sort($keys, SORT_STRING | SORT_FLAG_CASE);

        return $keys;
    }

    /**
     * @param  list<string>  $keys
     * @param  list<string>  $locales
     */
    private function mergeKeysIntoLocales(array $keys, array $locales): void
    {
        foreach (array_unique(['en', ...$locales]) as $locale) {
            $path = lang_path("{$locale}.json");

            if (! File::exists($path)) {
                continue;
            }

            $translations = $this->readJsonObject($path);

            foreach ($keys as $key) {
                if (! array_key_exists($key, $translations)) {
                    $translations[$key] = $key;
                }
            }

            $this->writeJsonObject($path, $translations);
        }
    }

    /**
     * @param  list<string>  $locales
     */
    private function syncEnglishKeysToLocales(array $locales): void
    {
        $english = $this->readJsonObject(lang_path('en.json'));

        foreach ($locales as $locale) {
            if ($locale === 'en') {
                continue;
            }

            $path = lang_path("{$locale}.json");

            if (! File::exists($path)) {
                continue;
            }

            $translations = $this->readJsonObject($path);

            foreach ($english as $key => $value) {
                if (! array_key_exists($key, $translations)) {
                    $translations[$key] = $value;
                }
            }

            $this->writeJsonObject($path, $translations);
        }
    }

    /**
     * @return array<string, string>
     */
    private function readJsonObject(string $path): array
    {
        $decoded = json_decode((string) File::get($path), true, flags: JSON_THROW_ON_ERROR);

        if (! is_array($decoded)) {
            return [];
        }

        /** @var array<string, string> $decoded */
        return $decoded;
    }

    /**
     * @param  array<string, string>  $translations
     */
    private function writeJsonObject(string $path, array $translations): void
    {
        ksort($translations, SORT_STRING | SORT_FLAG_CASE);

        File::put(
            $path,
            json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)."\n"
        );
    }
}
