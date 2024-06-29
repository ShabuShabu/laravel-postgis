<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\GeoHash;
use ShabuShabu\PostGIS\Expressions\Multi;

it('calculates the geo hash for a geom')
    ->expect(new GeoHash('geom'))
    ->toBeExpression('ST_GeoHash("geom")');

it('calculates the geo hash for a geom using a max no of characters')
    ->expect(new GeoHash('geom', 9))
    ->toBeExpression('ST_GeoHash("geom", 9)');

it('calculates the geo hash for a geom expression')
    ->expect(new GeoHash(new Multi('geom')))
    ->toBeExpression('ST_GeoHash(ST_Multi("geom"))');
