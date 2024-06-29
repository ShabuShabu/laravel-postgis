<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\MakePoint;
use Tpetry\QueryExpressions\Value\Number;

it('creates a 2d point from coordinates')
    ->expect(new MakePoint(10.234, 45.29439))
    ->toBeExpression('ST_MakePoint(10.234, 45.29439)');

it('creates a 3d point from coordinates')
    ->expect(new MakePoint(10.234, 45.29439, 273))
    ->toBeExpression('ST_MakePoint(10.234, 45.29439, 273)');

it('creates a 4d point from coordinates')
    ->expect(new MakePoint(10.234, 45.29439, 273, 1719652015))
    ->toBeExpression('ST_MakePoint(10.234, 45.29439, 273, 1719652015)');

it('creates a 4d point from coordinate expressions')
    ->expect(new MakePoint(new Number(10.234), new Number(45.29439), new Number(273), new Number(1719652015)))
    ->toBeExpression('ST_MakePoint(10.234, 45.29439, 273, 1719652015)');
