<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Casts\AsNumeric;
use Tpetry\QueryExpressions\Value\Value;

it('casts a column to numeric')
    ->expect(new AsNumeric('geom'))
    ->toBeExpression('"geom"::numeric');

it('casts a column to numeric expression')
    ->expect(new AsNumeric(new Value('2')))
    ->toBeExpression("'2'::numeric");
