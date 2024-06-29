<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Box\TwoD;
use ShabuShabu\PostGIS\Expressions\Collect;

it('can get the 2D box for a geom')
    ->expect(new TwoD('geom'))
    ->toBeExpression('Box2D("geom")');

it('can get the 2D box for a geom expression')
    ->expect(new TwoD(new Collect('geom')))
    ->toBeExpression('Box2D(ST_Collect("geom"))');
