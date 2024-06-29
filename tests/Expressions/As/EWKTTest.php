<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;

it('gets the EWKT representation of a geom')
    ->expect(new As\EWKT('geom'))
    ->toBeExpression('ST_AsEWKT("geom", 15)');

it('gets the EWKT representation of a geom expression')
    ->expect(new As\EWKT(new Collect('geom'), 12))
    ->toBeExpression('ST_AsEWKT(ST_Collect("geom"), 12)');
