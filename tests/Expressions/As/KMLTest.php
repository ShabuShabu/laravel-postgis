<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;

it('returns a KML representation')
    ->expect(new As\KML('geom'))
    ->toBeExpression('ST_AsKML("geom")');

it('returns a KML representation with params')
    ->expect(new As\KML('geom', 12, 'pp'))
    ->toBeExpression('ST_AsKML("geom", 12, \'pp\')');
