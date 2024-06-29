<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\From;

it('gets a geom from extended well-known binary')
    ->expect(new From\EWKB('ewkb'))
    ->toBeExpression('ST_GeomFromEWKB("ewkb")');

it('gets a geom from an extended well-known binary expression')
    ->expect(new From\EWKB(new As\EWKB('geom')))
    ->toBeExpression('ST_GeomFromEWKB(ST_AsEWKB("geom"))');
