<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\From;

it('gets a geom freom a well-known binary')
    ->expect(new From\WKB('wkb'))
    ->toBeExpression('ST_GeomFromWKB("wkb")');

it('gets a geom from a well-known binary with srid')
    ->expect(new From\WKB('wkb', 4326))
    ->toBeExpression('ST_GeomFromWKB("wkb", 4326)');

it('gets a geom freom a well-known binary expression')
    ->expect(new From\WKB(new As\Binary('geom')))
    ->toBeExpression('ST_GeomFromWKB(ST_AsBinary("geom"))');
