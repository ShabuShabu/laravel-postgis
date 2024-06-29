<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Enums\Endian;

it('gets the binary representation of a geom')
    ->expect(new As\Binary('geom'))
    ->toBeExpression('ST_AsBinary("geom")');

it('gets the binary representation of a geom expression')
    ->expect(new As\Binary(new Collect('geom'), Endian::big))
    ->toBeExpression('ST_AsBinary(ST_Collect("geom"), \'XDR\')');
