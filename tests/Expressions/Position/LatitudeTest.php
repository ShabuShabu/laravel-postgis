<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\Latitude;
use ShabuShabu\PostGIS\Expressions\Position\MakePoint;

it('gets the latitude for a point')
    ->expect(new Latitude('geom'))
    ->toBeExpression('ST_Y("geom")');

it('gets the latitude for a point expression')
    ->expect(new Latitude(new MakePoint(10.234, 45.29439)))
    ->toBeExpression('ST_Y(ST_MakePoint(10.234, 45.29439))');
