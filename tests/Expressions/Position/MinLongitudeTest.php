<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\ThreeD;
use ShabuShabu\PostGIS\Expressions\Position\MinLongitude;

it('gets the min longitude for a geom')
    ->expect(new MinLongitude('geom'))
    ->toBeExpression('ST_XMin("geom")');

it('gets the min longitude for an expression')
    ->expect(new MinLongitude(new ThreeD('geom')))
    ->toBeExpression('ST_XMin(Box3D("geom"))');
