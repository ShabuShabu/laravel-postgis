<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Centroid;
use ShabuShabu\PostGIS\Expressions\Collect;

it('gets geometry centers')
    ->expect(new Centroid('geom'))
    ->toBeExpression('ST_Centroid("geom")');

it('gets geography centers')
    ->expect(new Centroid('geog', false))
    ->toBeExpression('ST_Centroid("geog", false)');

it('gets geometry expression centers')
    ->expect(new Centroid(new Collect('geom')))
    ->toBeExpression('ST_Centroid(ST_Collect("geom"))');
