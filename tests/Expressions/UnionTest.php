<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Multi;
use ShabuShabu\PostGIS\Expressions\Union;

it('unionizes geoms')
    ->expect(new Union('geom'))
    ->toBeExpression('ST_Union("geom")');

it('unionizes geoms from an expression')
    ->expect(new Union(new Multi('geom')))
    ->toBeExpression('ST_Union(ST_Multi("geom"))');
