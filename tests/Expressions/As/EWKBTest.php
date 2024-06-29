<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Enums\Endian;

it('gets the EWKB representation of a geom')
    ->expect(new As\EWKB('geom'))
    ->toBeExpression('ST_AsEWKB("geom")');

it('gets the EWKB representation of a geom expression')
    ->expect(new As\EWKB(new Collect('geom'), Endian::big))
    ->toBeExpression('ST_AsEWKB(ST_Collect("geom"), \'XDR\')');
