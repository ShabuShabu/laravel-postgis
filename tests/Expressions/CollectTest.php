<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Multi;

it('collects geom columns')
    ->expect(new Collect('geom'))
    ->toBeExpression('ST_Collect("geom")');

it('collects two geometries')
    ->expect(new Collect('geom1', 'geom2'))
    ->toBeExpression('ST_Collect("geom1", "geom2")');

it('collects two geometry expressions')
    ->expect(new Collect(new Multi('geom1'), new Multi('geom2')))
    ->toBeExpression('ST_Collect(ST_Multi("geom1"), ST_Multi("geom2"))');
