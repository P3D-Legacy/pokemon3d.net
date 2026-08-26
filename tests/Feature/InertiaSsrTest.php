<?php

use Illuminate\Support\Facades\Http;

test('inertia responses include server-rendered html when ssr succeeds', function () {
    $this->withoutVite();

    config([
        'inertia.ssr.enabled' => true,
        'inertia.ssr.ensure_bundle_exists' => false,
        'inertia.ssr.url' => 'http://127.0.0.1:13714',
    ]);

    $ssrPayload = [
        'head' => ['<title inertia>SSR Legal</title>'],
        'body' => '<div id="app" data-server-rendered="true">ssr-legal-marker</div>',
    ];

    Http::fake(function ($request) use ($ssrPayload) {
        $url = $request->url();

        if (str_ends_with($url, '/render') || str_contains($url, '/__inertia_ssr')) {
            return Http::response($ssrPayload);
        }
    });

    $this->get(route('legal'))
        ->assertOk()
        ->assertSee('ssr-legal-marker', false)
        ->assertSee('data-server-rendered="true"', false)
        ->assertSee('SSR Legal', false);

    Http::assertSent(function ($request) {
        $url = $request->url();

        return str_ends_with($url, '/render') || str_contains($url, '/__inertia_ssr');
    });
});

test('inertia falls back to client rendering when ssr is disabled', function () {
    $this->withoutVite();

    config([
        'inertia.ssr.enabled' => false,
    ]);

    Http::fake([
        'http://127.0.0.1:13714/*' => Http::response(['error' => 'should not be called'], 500),
        '*__inertia_ssr*' => Http::response(['error' => 'should not be called'], 500),
    ]);

    $this->get(route('legal'))
        ->assertOk()
        ->assertSee('data-page="app"', false)
        ->assertDontSee('ssr-legal-marker');

    Http::assertNothingSent();
});
