<?php

it('only references strategy classes that exist', function () {
    $strategies = config('scribe.strategies');

    expect($strategies)->toBeArray()->not->toBeEmpty();

    foreach ($strategies as $stage => $stageStrategies) {
        expect($stageStrategies)->toBeArray();

        foreach ($stageStrategies as $strategy) {
            $strategyClass = is_array($strategy) ? $strategy[0] : $strategy;

            expect($strategyClass)
                ->toBeString()
                ->and(class_exists($strategyClass))
                ->toBeTrue("Missing Scribe strategy for [{$stage}]: {$strategyClass}");
        }
    }
});
