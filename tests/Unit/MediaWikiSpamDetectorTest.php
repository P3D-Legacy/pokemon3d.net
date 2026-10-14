<?php

use App\Support\MediaWikiSpamDetector;
use Tests\TestCase;

uses(TestCase::class);

test('commercial supplement pages are spam', function () {
    $detector = new MediaWikiSpamDetector;

    expect($detector->isSpam(
        'Fast Burn Keto: Avoiding Common Mistakes While Using Keto Supplements',
        'Fast Burn Keto is a dietary supplement that claims to help people achieve their weight loss goals.'
    ))->toBeTrue();
});

test('payday loan and casino pages are spam', function () {
    $detector = new MediaWikiSpamDetector;

    expect($detector->isSpam('Faxless Payday Loans: Supply Of Money', 'No collateral required for this payday loan.'))->toBeTrue()
        ->and($detector->isSpam('Finding Best Canadian Online Casino', 'Play real money online casino games today.'))->toBeTrue();
});

test('cyrillic commercial pages without game terms are spam', function () {
    $detector = new MediaWikiSpamDetector;

    expect($detector->isSpam(
        'Dosaaf',
        'Обучение категории Б: путь к уверенному вождению. В современном мире водительские права нужны каждому.'
    ))->toBeTrue();
});

test('pages created by blocked users are spam', function () {
    $detector = new MediaWikiSpamDetector(['HassanCramsie68']);

    expect($detector->isSpam('Random Notes', 'Just some leftover text on the wiki.', 'HassanCramsie68'))->toBeTrue();
});

test('pages created by product spam usernames are spam', function () {
    $detector = new MediaWikiSpamDetector;

    expect($detector->isSpam(
        'Supplement Guide',
        'A short page about pills and nothing else.',
        'FastBurnKetoReviews'
    ))->toBeTrue();
});

test('legitimate wiki pages are not spam', function () {
    $detector = new MediaWikiSpamDetector(['HassanCramsie68']);

    expect($detector->isSpam('Requirements', "== Requirements ==\n\n'''Windows''' is required.\n\n* Keyboard\n"))->toBeFalse()
        ->and($detector->isSpam(
            'Blackthorn City',
            'Blackthorn City is a city located in northeast Johto. Visit the gym leader after earning the other badges.',
            'Nilllzz'
        ))->toBeFalse()
        ->and($detector->isSpam(
            'Attack Scripting Portal',
            'Attack Scripting involves using LUA scripts to create attacks for pokemon. Tools include Decoda.',
            'Darkfire'
        ))->toBeFalse();
});
