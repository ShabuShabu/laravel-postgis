<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ShabuShabu\PostGIS\Servers\Features;
use ShabuShabu\PostGIS\Servers\Tiles;

if (config('postgis.tiles.enabled') && ! config('postgis.tiles.disable_default_route')) {
    Route::middleware(config('postgis.tiles.middleware'))->group(function () {
        Route::get(config('postgis.tiles.route_prefix') . '/{sourceName}/{z}/{x}/{y}.pbf', Tiles\Controller::class)
            ->name('tile-server');
    });
}

if (config('postgis.features.enabled') && ! config('postgis.features.disable_default_route')) {
    Route::middleware(config('postgis.features.middleware'))->group(function () {
        Route::get(config('postgis.features.route_prefix') . '/{uid}.geojson', Features\Controller::class)
            ->name('feature-server');
    });
}
