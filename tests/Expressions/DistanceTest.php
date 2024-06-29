<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Distance;
use ShabuShabu\PostGIS\Expressions\Multi;

it('can get the distance between geometry columns')
    ->expect(new Distance('geom1', 'geom2'))
    ->toBeExpression('ST_Distance("geom1", "geom2")');

it('can get the distance between geography columns')
    ->expect(new Distance('geog1', 'geog2', true))
    ->toBeExpression('ST_Distance("geog1", "geog2", true)');

it('can get the distance between geometry expression columns')
    ->expect(new Distance(new Multi('geom1'), new Multi('geom2')))
    ->toBeExpression('ST_Distance(ST_Multi("geom1"), ST_Multi("geom2"))');
