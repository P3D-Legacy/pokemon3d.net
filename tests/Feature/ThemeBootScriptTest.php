<?php

test('inertia root html includes the theme boot script', function () {
    $this->withoutVite();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-theme-boot', false)
        ->assertSee("localStorage.getItem('theme')", false)
        ->assertSee("classList.toggle('dark'", false)
        ->assertSee('style.colorScheme = theme', false);
});

test('app stylesheet keeps native select options readable in dark mode', function () {
    $css = file_get_contents(resource_path('css/app.css'));

    expect($css)
        ->toContain('color-scheme: dark')
        ->toContain('select option')
        ->toContain('background-color: #ffffff')
        ->toContain('color: #111111');
});
