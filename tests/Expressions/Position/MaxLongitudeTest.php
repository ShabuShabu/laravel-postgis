<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\ThreeD;
use ShabuShabu\PostGIS\Expressions\Position\MaxLongitude;

it('gets the max longitude for a geom')
    ->expect(new MaxLongitude('geom'))
    ->toBeExpression('ST_XMax("geom")');

it('gets the max longitude for an expression')
    ->expect(new MaxLongitude(new ThreeD('geom')))
    ->toBeExpression('ST_XMax(Box3D("geom"))');
