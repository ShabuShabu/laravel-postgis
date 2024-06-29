<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Intersects;
use ShabuShabu\PostGIS\Expressions\Multi;

it('can intersect two geoms')
    ->expect(new Intersects('geom1', 'geom2'))
    ->toBeExpression('ST_Intersects("geom1", "geom2")');

it('can intersect two geom expressions')
    ->expect(new Intersects(new Multi('geom1'), new Multi('geom2')))
    ->toBeExpression('ST_Intersects(ST_Multi("geom1"), ST_Multi("geom2"))');
