<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\DWithin;
use ShabuShabu\PostGIS\Expressions\Multi;

it('compares geometries within a given distance')
    ->expect(new DWithin('geom1', 'geom2', 3000))
    ->toBeExpression('ST_DWithin("geom1", "geom2", 3000)');

it('compares geographies within a given distance')
    ->expect(new DWithin('geom1', 'geom2', 3000, true))
    ->toBeExpression('ST_DWithin("geom1", "geom2", 3000, true)');

it('compares geometry expressions within a given distance')
    ->expect(new DWithin(new Multi('geom1'), new Multi('geom2'), 3000))
    ->toBeExpression('ST_DWithin(ST_Multi("geom1"), ST_Multi("geom2"), 3000)');
