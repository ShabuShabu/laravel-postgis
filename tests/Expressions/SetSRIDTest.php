<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Multi;
use ShabuShabu\PostGIS\Expressions\SetSRID;

it('sets a new srid')
    ->expect(new SetSRID('geom', 2163))
    ->toBeExpression('ST_SetSRID("geom", 2163)');

it('sets a new expression srid')
    ->expect(new SetSRID(new Multi('geom'), 2163))
    ->toBeExpression('ST_SetSRID(ST_Multi("geom"), 2163)');
