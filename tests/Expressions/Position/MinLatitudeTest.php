<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\ThreeD;
use ShabuShabu\PostGIS\Expressions\Position\MinLatitude;

it('gets the min latitude for a geom')
    ->expect(new MinLatitude('geom'))
    ->toBeExpression('ST_YMin("geom")');

it('gets the min latitude for an expression')
    ->expect(new MinLatitude(new ThreeD('geom')))
    ->toBeExpression('ST_YMin(Box3D("geom"))');
