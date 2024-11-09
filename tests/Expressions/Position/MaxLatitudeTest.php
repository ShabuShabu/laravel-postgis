<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\ThreeD;
use ShabuShabu\PostGIS\Expressions\Position\MaxLatitude;

it('gets the max latitude for a geom')
    ->expect(new MaxLatitude('geom'))
    ->toBeExpression('ST_YMax("geom")');

it('gets the max latitude for an expression')
    ->expect(new MaxLatitude(new ThreeD('geom')))
    ->toBeExpression('ST_YMax(Box3D("geom"))');
