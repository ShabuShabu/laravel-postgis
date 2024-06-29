<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Dump;

it('gets the flat geobuf representation of a geom')
    ->expect(new As\FlatGeobuf('geom'))
    ->toBeExpression('ST_AsFlatGeobuf("geom", false)');

it('gets the flat geobuf representation of a geom expression')
    ->expect(new As\FlatGeobuf(new Dump('geom'), true, 'line'))
    ->toBeExpression('ST_AsFlatGeobuf(ST_Dump("geom"), true, \'line\')');
