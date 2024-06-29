<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Multi;
use ShabuShabu\PostGIS\Expressions\Transform;

it('transforms geoms from one srid to another')
    ->expect(new Transform('geom', 2163))
    ->toBeExpression('ST_Transform("geom", 2163)');

it('transforms expressions from one srid to another')
    ->expect(new Transform(new Multi('geom'), 2163))
    ->toBeExpression('ST_Transform(ST_Multi("geom"), 2163)');
