<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Math\Round;
use Tpetry\QueryExpressions\Value\Number;

it('rounds a float')
    ->expect(new Round(1.34))
    ->toBeExpression('round(1.34, 0)');

it('rounds a column string')
    ->expect(new Round('column'))
    ->toBeExpression('round("column", 0)');

it('rounds an expression')
    ->expect(new Round(new Number(1.34), 2))
    ->toBeExpression('round(1.34, 2)');
