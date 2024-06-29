<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\Longitude;
use ShabuShabu\PostGIS\Expressions\Position\MakePoint;

it('gets the longitude for a point')
    ->expect(new Longitude('geom'))
    ->toBeExpression('ST_X("geom")');

it('gets the longitude for a point expression')
    ->expect(new Longitude(new MakePoint(10.234, 45.29439)))
    ->toBeExpression('ST_X(ST_MakePoint(10.234, 45.29439))');
