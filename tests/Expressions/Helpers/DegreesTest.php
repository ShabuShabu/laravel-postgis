<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Helpers\Degrees;

it('transforms floats to degrees')
    ->expect(new Degrees(1.5))
    ->toBeExpression('degrees(1.5)');

it('transforms columns to degrees')
    ->expect(new Degrees('bearing'))
    ->toBeExpression('degrees("bearing")');

it('transforms expressions to degrees')
    ->expect(new Degrees(new \Tpetry\QueryExpressions\Value\Number(1.5)))
    ->toBeExpression('degrees(1.5)');
