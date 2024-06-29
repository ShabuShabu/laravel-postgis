<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Area;
use ShabuShabu\PostGIS\Expressions\Collect;

it('calculates the geometry area')
    ->expect(new Area('geom'))
    ->toBeExpression('ST_Area("geom")');

it('calculates the geography area')
    ->expect(new Area('geog', true))
    ->toBeExpression('ST_Area("geog", true)');

it('calculates the geometry expression area')
    ->expect(new Area(new Collect('geom')))
    ->toBeExpression('ST_Area(ST_Collect("geom"))');
