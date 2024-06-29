<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Multi;

it('gets the TWKB representation of a geom')
    ->expect(new As\TWKB('geom'))
    ->toBeExpression('ST_AsTWKB("geom", 0, 0, 0, false, false)');

it('gets the TWKB representation of a geom expression')
    ->expect(new As\TWKB(new Multi('geom'), 5, 4, 4, true, true))
    ->toBeExpression('ST_AsTWKB(ST_Multi("geom"), 5, 4, 4, true, true)');
