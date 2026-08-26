<?php

use Inertia\Testing\AssertableInertia as Assert;

test('shared language props include the configured non-production languages', function () {
    config()->set('app.env', 'local');
    config()->set('language.allowed', ['en', 'nb', 'it', 'pt-BR']);
    config()->set('language.done', ['en', 'nb']);
    app()->setLocale('en');

    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('locale', 'en')
            ->where('languages.current', 'en')
            ->where('languages.current_flag', 'us')
            ->has('languages.options', 4)
            ->where('languages.options.0.code', 'en')
            ->where('languages.options.1.code', 'nb')
            ->where('languages.options.2.code', 'it')
            ->where('languages.options.3.code', 'pt-BR')
            ->where('languages.options.0.url', route('language::back', ['locale' => 'en']))
            ->has('languages.contribute_url')
            ->has('languages.contribute_label'));
});

test('shared language props use done languages in production', function () {
    config()->set('app.env', 'production');
    config()->set('language.allowed', ['en', 'nb', 'it', 'sv']);
    config()->set('language.done', ['en', 'nb', 'it']);
    app()->setLocale('nb');

    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('languages.current', 'nb')
            ->where('languages.current_flag', 'no')
            ->has('languages.options', 3)
            ->where('languages.options.0.code', 'en')
            ->where('languages.options.1.code', 'nb')
            ->where('languages.options.2.code', 'it'));
});

test('language switch route updates the session locale for guests', function () {
    $this->from(route('login'))
        ->get(route('language::back', ['locale' => 'nb']))
        ->assertRedirect(route('login'));

    expect(session('locale'))->toBe('nb');
});

test('allowed language translation files include every english key', function () {
    $english = json_decode(
        (string) file_get_contents(lang_path('en.json')),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    expect($english)->toBeArray();

    foreach (config('language.allowed') as $code) {
        $path = lang_path("{$code}.json");

        expect(file_exists($path))->toBeTrue("Missing language file for [{$code}].");

        $translations = json_decode(
            (string) file_get_contents($path),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $missing = array_diff_key($english, $translations);

        expect($missing)->toBeEmpty("Language [{$code}] is missing keys: ".implode(', ', array_keys($missing)));
    }
});

test('production done languages are a subset of allowed languages', function () {
    $allowed = config('language.allowed');
    $done = config('language.done');

    expect(array_diff($done, $allowed))->toBeEmpty();
    expect($done)->toContain('it');
    expect($done)->toContain('en-GB');
});

test('shared translations include english keys for the current locale', function () {
    app()->setLocale('en');

    $this->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->has('translations')
            ->where('translations.Home', 'Home')
            ->where('translations.Dashboard', 'Dashboard'));
});

test('british english is selectable and uses the gb flag', function () {
    config()->set('app.env', 'local');

    $this->withSession(['locale' => 'en-GB'])
        ->get(route('login'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('languages.current', 'en-GB')
            ->where('languages.current_name', 'British English')
            ->where('languages.current_flag', 'gb')
            ->where('languages.options.1.code', 'en-GB')
            ->where('languages.options.1.flag', 'gb')
            ->where('languages.options.1.url', route('language::back', ['locale' => 'en-GB'])));
});
