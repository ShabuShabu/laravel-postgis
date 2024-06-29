<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\DumpPoints;

it('dumps geometry coordinates')
    ->expect(new DumpPoints('geom'))
    ->toBeExpression('ST_DumpPoints("geom")');

it('dumps geometry coordinates and extracts a geometry column')
    ->expect(new DumpPoints('geom', 'geom'))
    ->toBeExpression('(ST_DumpPoints("geom"))."geom"');

it('dumps geometry expression coordinates')
    ->expect(new DumpPoints(new Collect('geom')))
    ->toBeExpression('ST_DumpPoints(ST_Collect("geom"))');
