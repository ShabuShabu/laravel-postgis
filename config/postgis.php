<?php

declare(strict_types=1);

use ShabuShabu\PostGIS\Import\Importers;
use ShabuShabu\PostGIS\Models;

return [
    'importers' => [
        'timezones' => Importers\Timezones::class,
        'continents' => Importers\Continents::class,
        'countries' => Importers\Countries::class,
        'provinces' => Importers\Provinces::class,
        'oceans' => Importers\Oceans::class,
        'seas' => Importers\Seas::class,
    ],

    'sources' => [
        'timezones' => storage_path('gis/timezones-with-oceans-now.shapefile.zip'),
        'continents' => storage_path('gis/World_Continents_-8398826466908339531.zip'),
        'countries' => storage_path('gis/ne_10m_admin_0_countries.zip'),
        'provinces' => storage_path('gis/ne_10m_admin_1_states_provinces.zip'),
        'oceans' => storage_path('gis/GOaS_v1_20211214.zip'),
        'seas' => storage_path('gis/World_Seas_IHO_v3.zip'),
    ],

    'models' => [
        'timezone' => Models\Timezone::class,
        'continent' => Models\Continent::class,
        'country' => Models\Country::class,
        'province' => Models\Province::class,
        'ocean' => Models\Ocean::class,
        'sea' => Models\Sea::class,
    ],

    'binaries' => [
        'psql' => env('POSTGIS_BINARY_PSQL', '/opt/homebrew/opt/postgresql@16/bin/psql'),
        'shp2pgsql' => env('POSTGIS_BINARY_SHP2PGSQL', '/opt/homebrew/bin/shp2pgsql'),
    ],
];
