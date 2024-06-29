<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Multi;

it('turns a geom into its multi variant')
    ->expect(new Multi('geom'))
    ->toBeExpression('ST_Multi("geom")');

it('turns a geom expression into its multi variant')
    ->expect(new Multi(new Collect('geom')))
    ->toBeExpression('ST_Multi(ST_Collect("geom"))');
