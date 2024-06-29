<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\From;

it('gets a geom from a tiny well-known binary')
    ->expect(new From\TWKB('twkb'))
    ->toBeExpression('ST_GeomFromTWKB("twkb")');

it('gets a geom from a tiny well-known binary expression')
    ->expect(new From\TWKB(new As\TWKB('geom')))
    ->toBeExpression('ST_GeomFromTWKB(ST_AsTWKB("geom", 0, 0, 0, false, false))');
