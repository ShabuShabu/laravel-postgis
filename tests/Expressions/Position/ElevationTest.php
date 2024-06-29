<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\Elevation;
use ShabuShabu\PostGIS\Expressions\Position\MakePoint;

it('gets the elevation for a point')
    ->expect(new Elevation('geom'))
    ->toBeExpression('ST_Z("geom")');

it('gets the elevation for a point expression')
    ->expect(new Elevation(new MakePoint(10.234, 45.29439, 273)))
    ->toBeExpression('ST_Z(ST_MakePoint(10.234, 45.29439, 273))');
