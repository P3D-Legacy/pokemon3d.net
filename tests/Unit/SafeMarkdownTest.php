<?php

use App\Support\SafeMarkdown;
use Tests\TestCase;

uses(TestCase::class);

test('safe markdown keeps ordinary formatting and links', function () {
    $html = SafeMarkdown::html('**bold** and [site](https://example.com)');

    expect($html)
        ->toContain('<strong>bold</strong>')
        ->toContain('href="https://example.com"');
});

test('safe markdown strips raw html event handlers', function (string $markdown) {
    $html = SafeMarkdown::html($markdown);

    expect($html)
        ->not->toContain('onerror')
        ->not->toContain('onload')
        ->not->toContain('ontoggle')
        ->not->toContain('<script')
        ->not->toContain('<img')
        ->not->toContain('<svg')
        ->not->toContain('<details');
})->with([
    '<img src=x onerror=alert(1)>',
    '<svg onload=alert(1)></svg>',
    '<details open ontoggle=alert(1)>x</details>',
    '<script>alert(1)</script>',
]);

test('safe markdown drops unsafe link schemes', function (string $markdown) {
    $html = SafeMarkdown::html($markdown);

    expect($html)
        ->toContain('click')
        ->not->toContain('javascript:')
        ->not->toContain('data:')
        ->not->toContain('href=');
})->with([
    '[click](javascript:alert(1))',
    '[click](data:text/html,hi)',
]);
