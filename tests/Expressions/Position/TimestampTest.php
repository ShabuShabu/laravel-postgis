<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\MakePoint;
use ShabuShabu\PostGIS\Expressions\Position\Timestamp;

it('gets the timestamp for a point')
    ->expect(new Timestamp('geom'))
    ->toBeExpression('ST_M("geom")');

it('gets the timestamp for a point expression')
    ->expect(new Timestamp(new MakePoint(10.234, 45.29439, 273, 1719652015)))
    ->toBeExpression('ST_M(ST_MakePoint(10.234, 45.29439, 273, 1719652015))');
