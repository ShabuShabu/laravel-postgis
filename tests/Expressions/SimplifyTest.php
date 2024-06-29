<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Multi;
use ShabuShabu\PostGIS\Expressions\Simplify;

it('simplifies geoms without preserving collapsed objects')
    ->expect(new Simplify('geom', 0.15))
    ->toBeExpression('ST_Simplify("geom", 0.15, false)');

it('simplifies geoms while preserving collapsed objects')
    ->expect(new Simplify('geom', 0.15, true))
    ->toBeExpression('ST_Simplify("geom", 0.15, true)');

it('simplifies geom expressions without preserving collapsed objects')
    ->expect(new Simplify(new Multi('geom'), 0.15))
    ->toBeExpression('ST_Simplify(ST_Multi("geom"), 0.15, false)');
