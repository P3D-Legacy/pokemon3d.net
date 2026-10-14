<?php

use Illuminate\Console\Scheduling\Schedule;

test('long scheduled commands do not overlap', function () {
    $commands = [
        'p3d:skinuserupdate',
        'discord:syncroles',
        'discord:syncuserroles',
        'github:syncrelease',
        'notify:gameupdate',
    ];

    $events = collect(app(Schedule::class)->events());

    foreach ($commands as $command) {
        $event = $events->first(fn ($scheduled) => str_contains((string) $scheduled->command, $command));

        expect($event)->not->toBeNull()
            ->and($event->withoutOverlapping)->toBeTrue();
    }
});
