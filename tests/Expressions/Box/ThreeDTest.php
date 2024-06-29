<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\ThreeD;
use ShabuShabu\PostGIS\Expressions\Collect;

it('can get the 2D box for a geom')
    ->expect(new ThreeD('geom'))
    ->toBeExpression('Box3D("geom")');

it('can get the 2D box for a geom expression')
    ->expect(new ThreeD(new Collect('geom')))
    ->toBeExpression('Box3D(ST_Collect("geom"))');
