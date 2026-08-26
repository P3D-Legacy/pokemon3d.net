<?php

use Illuminate\Support\Facades\Artisan;

it('does not register schedule monitor commands', function () {
    expect(array_key_exists('schedule-monitor:sync', Artisan::all()))->toBeFalse();
});

it('does not ship schedule monitor config', function () {
    expect(file_exists(config_path('schedule-monitor.php')))->toBeFalse();
});
