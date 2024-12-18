<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\TileEnvelope;

it('expresses a tile envelope')
    ->expect(new TileEnvelope(1, 23, 34))
    ->toBeExpression('ST_TileEnvelope(zoom => 1, x => 23, y => 34)');
