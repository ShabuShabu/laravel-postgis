<?php

declare(strict_types=1);

use ShabuShabu\PostGIS\Models;
use ShabuShabu\PostGIS\Import\Importers;

return [
    'importers' => [
        'timezones' => Importers\Timezones::class,
        'continents' => Importers\Continents::class,
        'countries' => Importers\Countries::class,
        'provinces' => Importers\Provinces::class,
        'oceans' => Importers\Oceans::class,
        'seas' => Importers\Seas::class,
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
        'psql'      => env('POSTGIS_BINARY_PSQL', '/opt/homebrew/opt/postgresql@16/bin/psql'),
        'shp2pgsql' => env('POSTGIS_BINARY_SHP2PGSQL', '/opt/homebrew/bin/shp2pgsql'),
    ],
];
