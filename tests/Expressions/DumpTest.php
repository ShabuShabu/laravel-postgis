<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Dump;

it('dumps geometry components')
    ->expect(new Dump('geom'))
    ->toBeExpression('ST_Dump("geom")');

it('dumps geometry components and extracts a geometry column')
    ->expect(new Dump('geom', 'geom'))
    ->toBeExpression('(ST_Dump("geom"))."geom"');

it('dumps geometry expression components')
    ->expect(new Dump(new Collect('geom')))
    ->toBeExpression('ST_Dump(ST_Collect("geom"))');
