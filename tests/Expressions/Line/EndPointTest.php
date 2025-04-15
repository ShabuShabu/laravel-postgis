<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\From;
use ShabuShabu\PostGIS\Expressions\Line\EndPoint;

it('gets the starting point from a column linestring')
    ->expect(new EndPoint('geom'))
    ->toBeExpression('ST_EndPoint("geom")');

it('gets the starting point from an expression linestring')
    ->expect(new EndPoint(new From\Text('LINESTRING(1 1, 2 2, 3 3)')))
    ->toBeExpression('ST_EndPoint(ST_GeomFromText("LINESTRING(1 1, 2 2, 3 3)"))');
