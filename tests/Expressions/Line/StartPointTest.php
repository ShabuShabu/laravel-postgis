<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\From;
use ShabuShabu\PostGIS\Expressions\Line\StartPoint;

it('gets the starting point from a column linestring')
    ->expect(new StartPoint('geom'))
    ->toBeExpression('ST_StartPoint("geom")');

it('gets the starting point from an expression linestring')
    ->expect(new StartPoint(new From\Text('LINESTRING(1 1, 2 2, 3 3)')))
    ->toBeExpression('ST_StartPoint(ST_GeomFromText("LINESTRING(1 1, 2 2, 3 3)"))');
