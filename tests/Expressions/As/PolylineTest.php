<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;

it('gets the polyline representation of a geom')
    ->expect(new As\Polyline('geom'))
    ->toBeExpression('ST_AsEncodedPolyline("geom", 5)');

it('gets the polyline representation of a geom expression')
    ->expect(new As\Polyline(new Collect('geom'), 6))
    ->toBeExpression('ST_AsEncodedPolyline(ST_Collect("geom"), 6)');
