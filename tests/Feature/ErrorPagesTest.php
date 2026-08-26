<?php

use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->withoutVite();
});

it('shows a branded 404 page', function () {
    $this->get('/this-page-definitely-does-not-exist-'.uniqid())
        ->assertNotFound()
        ->assertSee('MissingNo.', false)
        ->assertSee('This path was not found.', false)
        ->assertSee('Go back home', false)
        ->assertSee(asset('img/missingno.png'), false)
        ->assertSee(asset('img/pokemon3d_logo.png'), false);
});

it('shows a branded 403 page', function () {
    Route::get('/__error-page-preview/403', fn () => abort(403));

    $this->get('/__error-page-preview/403')
        ->assertForbidden()
        ->assertSee('Access denied', false)
        ->assertSee('403', false)
        ->assertSee('Go back home', false);
});

it('shows a branded 500 page', function () {
    Route::get('/__error-page-preview/500', fn () => abort(500));

    $this->get('/__error-page-preview/500')
        ->assertStatus(500)
        ->assertSee('Server Error', false)
        ->assertSee('500', false)
        ->assertSee('Go back home', false);
});

it('shows a branded 503 page with a status link', function () {
    Route::get('/__error-page-preview/503', fn () => abort(503));

    $this->get('/__error-page-preview/503')
        ->assertStatus(503)
        ->assertSee('Maintenance', false)
        ->assertSee('https://status.pokemon3d.net', false)
        ->assertSee('status.pokemon3d.net', false)
        ->assertSee('Go back home', false);
});

it('shows a branded 504 page', function () {
    Route::get('/__error-page-preview/504', fn () => abort(504));

    $this->get('/__error-page-preview/504')
        ->assertStatus(504)
        ->assertSee('Timed Out', false)
        ->assertSee('504', false)
        ->assertSee('Go back home', false);
});
