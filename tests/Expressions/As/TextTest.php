<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;

it('gets the wkt representation of a geom')
    ->expect(new As\Text('geom'))
    ->toBeExpression('ST_AsText("geom", 15)');

it('gets the wkt representation of a geom expression')
    ->expect(new As\Text(new Collect('geom'), 12))
    ->toBeExpression('ST_AsText(ST_Collect("geom"), 12)');
