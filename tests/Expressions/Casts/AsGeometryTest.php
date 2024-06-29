<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Casts\AsGeometry;
use Tpetry\QueryExpressions\Value\Value;

it('casts a column to geometry')
    ->expect(new AsGeometry('geom'))
    ->toBeExpression('"geom"::geometry');

it('casts a column to geometry expression')
    ->expect(new AsGeometry(new Value('POINT(-21.96 64.15)')))
    ->toBeExpression("'POINT(-21.96 64.15)'::geometry");
