<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\MemUnion;
use ShabuShabu\PostGIS\Expressions\Multi;

it('efficiently unionizes geoms')
    ->expect(new MemUnion('geom'))
    ->toBeExpression('ST_MemUnion("geom")');

it('efficiently unionizes geom expressions')
    ->expect(new MemUnion(new Multi('geom')))
    ->toBeExpression('ST_MemUnion(ST_Multi("geom"))');
