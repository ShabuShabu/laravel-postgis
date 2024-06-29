<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Enums\Endian;

it('gets the HEXEWKB representation of a geom')
    ->expect(new As\HEXEWKB('geom'))
    ->toBeExpression('ST_AsHEXEWKB("geom", \'NDR\')');

it('gets the HEXEWKB representation of a geom expression')
    ->expect(new As\HEXEWKB(new Collect('geom'), Endian::big))
    ->toBeExpression('ST_AsHEXEWKB(ST_Collect("geom"), \'XDR\')');
