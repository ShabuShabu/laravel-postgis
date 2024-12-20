<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | The domain the servers are gonna run in
    |--------------------------------------------------------------------------
    |
    | Leave empty if you do not want to specify a domain.
    |
    */
    'server_domain' => env('POSTGIS_SERVER_DOMAIN', ''),

    'tiles' => [
        /*
        |--------------------------------------------------------------------------
        | Enable/disable the tile server
        |--------------------------------------------------------------------------
        |
        | Just a boolean flag to check if the server is enabled. Set to `false`
        | by default.
        |
        */
        'enabled' => env('POSTGIS_TILES_ENABLED', false),

        /*
        |--------------------------------------------------------------------------
        | Sources
        |--------------------------------------------------------------------------
        |
        | Here you need to add all available tile sources. All sources need to
        | implement the `ShabuShabu\PostGIS\Servers\Tile\Contracts\Sourceable`
        | interface.
        |
        | Here is an example:
        | `App\Services\MVT\CountrySource::class`
        |
        */
        'sources' => [],

        /*
        |--------------------------------------------------------------------------
        | Disable the default tile route
        |--------------------------------------------------------------------------
        |
        | Just a boolean flag if the default route should be disabled. Useful
        | if you want to run your own setup!
        |
        */
        'disable_default_route' => false,

        /*
        |--------------------------------------------------------------------------
        | Tile route prefix
        |--------------------------------------------------------------------------
        |
        | Change the route prefix for the tile server URL here. By default, this
        | will generate URLs like so:
        | `https://your-site.com/services/tiles/{source}/{z}/{x}/{y}.pbf`
        |
        */
        'route_prefix' => 'services/tiles',

        /*
        |--------------------------------------------------------------------------
        | Tile route middleware
        |--------------------------------------------------------------------------
        |
        | Add any route middleware the tile server may need.
        |
        */
        'middleware' => [],

        /*
        |--------------------------------------------------------------------------
        | Cache-Control
        |--------------------------------------------------------------------------
        |
        | Here you can specify the cache-control header for the streamed tile
        | server response.
        |
        */
        'cache_control' => 'max-age=604800',
    ],

    'features' => [
        /*
        |--------------------------------------------------------------------------
        | Enable/disable the feature server
        |--------------------------------------------------------------------------
        |
        | Just a boolean flag to check if the server is enabled. Set to `false`
        | by default.
        |
        */
        'enabled' => env('POSTGIS_FEATURES_ENABLED', false),

        /*
        |--------------------------------------------------------------------------
        | Disable the default feature route
        |--------------------------------------------------------------------------
        |
        | Just a boolean flag if the default route should be disabled. Useful
        | if you want to run your own setup!
        |
        */
        'disable_default_route' => false,

        /*
        |--------------------------------------------------------------------------
        | Feature route prefix
        |--------------------------------------------------------------------------
        |
        | Change the route prefix for the feature server URL here. By default, this
        | will generate URLs like so:
        | `https://your-site.com/services/features/{uid}.geojson`
        |
        */
        'route_prefix' => 'services/features',

        /*
        |--------------------------------------------------------------------------
        | Feature route middleware
        |--------------------------------------------------------------------------
        |
        | Add any route middleware the tile server may need.
        |
        */
        'middleware' => [],

        /*
        |--------------------------------------------------------------------------
        | Cache TTL
        |--------------------------------------------------------------------------
        |
        | The number of minutes a GeoJson response should be cached. Set to `null`
        | to disable.
        |
        */
        'cache_ttl' => 60,
    ],
];
