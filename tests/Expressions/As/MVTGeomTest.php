<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;

it('transforms a geometry into the coordinate space of a tile')
    ->expect(new As\MVTGeom('geom', 'bbox'))
    ->toBeExpression('ST_AsMVTGeom("geom", "bbox", 4096, 256, true)');
