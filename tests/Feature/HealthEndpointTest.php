<?php

it('exposes the framework health endpoint without Spatie Health', function () {
    expect(class_exists('Spatie\\Health\\HealthServiceProvider'))->toBeFalse();

    $this->get('/up')->assertOk();
});
