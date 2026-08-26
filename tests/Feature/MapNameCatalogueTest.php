<?php

use App\Support\MapNameCatalogue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

test('map name catalogue resolves worldmap place names and humanizes unknowns', function () {
    Http::fake([
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Data/Scripts/worldmap/*' => Http::sequence()
            ->push(<<<'RAW'
{"PlaceType"[City]}{"Name"[Azalea Town]}{"MapFiles"[azalea.dat,azalea\center.dat]}
{"PlaceType"[Route]}{"Name"[Route 29]}{"MapFiles"[route29.dat,gates\route2946gate.dat]}
{"PlaceType"[Place]}{"Name"[Safari Zone]}{"Mapfiles"[safarizone\main.dat,safarizone\center.dat]}
RAW)
            ->push('')
            ->push('')
            ->push(''),
    ]);

    expect(MapNameCatalogue::name('azalea.dat'))->toBe('Azalea Town')
        ->and(MapNameCatalogue::name('johto\routes\route29.dat'))->toBe('Route 29')
        ->and(MapNameCatalogue::name('safarizone/main.dat'))->toBe('Safari Zone')
        ->and(MapNameCatalogue::name('customarea\\hiddennook.dat'))->toBe('Hiddennook');
});

test('map name catalogue humanizes paths when worldmap data is unavailable', function () {
    Http::fake([
        'https://raw.githubusercontent.com/P3D-Legacy/P3D-Legacy/master/P3D/Content/Data/Scripts/worldmap/*' => Http::response('', 500),
    ]);

    expect(MapNameCatalogue::name('route39.dat'))->toBe('Route 39')
        ->and(MapNameCatalogue::name('routes\\route43.dat'))->toBe('Route 43')
        ->and(MapNameCatalogue::name(''))->toBe('Unknown location');
});
