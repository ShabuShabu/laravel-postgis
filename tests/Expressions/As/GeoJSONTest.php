<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Enums\Option;

it('gets the GeoJSON representation of a geom 1')
    ->expect(new As\GeoJSON('geom'))
    ->toBeExpression('ST_AsGeoJSON("geom", 9, 8)');

it('gets the GeoJSON representation of a geom 2')
    ->expect(new As\GeoJSON('geom', null, null))
    ->toBeExpression('ST_AsGeoJSON("geom")');

it('gets the GeoJSON representation of a geom expression')
    ->expect(new As\GeoJSON(new Collect('geom'), 6, Option::bbox))
    ->toBeExpression('ST_AsGeoJSON(ST_Collect("geom"), 6, 1)');
