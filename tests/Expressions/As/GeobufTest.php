<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Dump;

it('gets the geobuf representation of a geom')
    ->expect(new As\Geobuf('geom'))
    ->toBeExpression('ST_AsGeobuf("geom")');

it('gets the geobuf representation of a geom expression')
    ->expect(new As\Geobuf(new Dump('geom'), 'line'))
    ->toBeExpression('ST_AsGeobuf(ST_Dump("geom"), \'line\')');
