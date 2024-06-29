<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Casts\AsGeography;
use Tpetry\QueryExpressions\Value\Value;

it('casts a column to geography')
    ->expect(new AsGeography('geom'))
    ->toBeExpression('"geom"::geography');

it('casts a column to geography expression')
    ->expect(new AsGeography(new Value('POINT(-21.96 64.15)')))
    ->toBeExpression("'POINT(-21.96 64.15)'::geography");
