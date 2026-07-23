<?php

test('inertia root html includes the theme boot script', function () {
    $this->withoutVite();

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('data-theme-boot', false)
        ->assertSee("localStorage.getItem('theme')", false)
        ->assertSee("classList.toggle('dark'", false);
});
