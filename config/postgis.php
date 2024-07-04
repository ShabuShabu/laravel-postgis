<?php

declare(strict_types=1);

use ShabuShabu\PostGIS\Import\Importers;
use ShabuShabu\PostGIS\Models;

return [
    'importers' => [
        'timezones' => Importers\Timezones::class,
        'continents' => Importers\Continents::class,
        'oceans' => Importers\Oceans::class,
        'seas' => Importers\Seas::class,
        'countries' => Importers\Countries::class,
        'provinces' => Importers\Provinces::class,
        'disputed_areas' => Importers\DisputedAreas::class,
    ],

    'sources' => [
        'timezones' => storage_path('gis/timezones-with-oceans-now.shapefile.zip'),
        'continents' => storage_path('gis/World_Continents_-8398826466908339531.zip'),
        'oceans' => storage_path('gis/GOaS_v1_20211214.zip'),
        'seas' => storage_path('gis/World_Seas_IHO_v3.zip'),
        'countries' => storage_path('gis/ne_10m_admin_0_countries.zip'),
        'provinces' => storage_path('gis/ne_10m_admin_1_states_provinces.zip'),
        'disputed_areas' => storage_path('gis/ne_10m_admin_0_disputed_areas.zip'),
    ],

    'models' => [
        'timezone' => Models\Timezone::class,
        'continent' => Models\Continent::class,
        'ocean' => Models\Ocean::class,
        'sea' => Models\Sea::class,
        'country' => Models\Country::class,
        'province' => Models\Province::class,
        'disputed_area' => Models\DisputedArea::class,
    ],

    'binaries' => [
        'psql' => env('POSTGIS_BINARY_PSQL', '/opt/homebrew/opt/postgresql@16/bin/psql'),
        'shp2pgsql' => env('POSTGIS_BINARY_SHP2PGSQL', '/opt/homebrew/bin/shp2pgsql'),
    ],
];
