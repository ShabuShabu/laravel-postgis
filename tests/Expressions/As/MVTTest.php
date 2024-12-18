<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;

it('returns a binary Mapbox Vector Tile representation')
    ->expect(new As\MVT('mvtgeom.*', 'default'))
    ->toBeExpression('ST_AsMVT("mvtgeom".*, \'default\')');
