<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Length;

it('calculates the geometry length')
    ->expect(new Length('geom'))
    ->toBeExpression('ST_Length("geom")');

it('calculates the geography length')
    ->expect(new Length('geog', true))
    ->toBeExpression('ST_Length("geog", true)');

it('calculates the geometry expression length')
    ->expect(new Length(new Collect('geom')))
    ->toBeExpression('ST_Length(ST_Collect("geom"))');
