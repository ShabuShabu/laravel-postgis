<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Contains;

it('can check if a geom contains another geom')
    ->expect(new Contains('geom1', 'geom2'))
    ->toBeExpression('ST_Contains("geom1", "geom2")');

it('can check if a geom expression contains another geom expression')
    ->expect(new Contains(new Collect('geom1'), new Collect('geom2')))
    ->toBeExpression('ST_Contains(ST_Collect("geom1"), ST_Collect("geom2"))');
