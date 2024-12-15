<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\CollectionExtract;
use ShabuShabu\PostGIS\Expressions\Enums\Type;

it('extracts linestrings from a geom collection')
    ->expect(new CollectionExtract('geom'))
    ->toBeExpression('ST_CollectionExtract("geom", 2)');

it('extracts points from a geom collection')
    ->expect(new CollectionExtract('geom', Type::point))
    ->toBeExpression('ST_CollectionExtract("geom", 1)');

it('extracts polygons from a geom collection expression')
    ->expect(new CollectionExtract(new Collect('geom'), Type::polygon))
    ->toBeExpression('ST_CollectionExtract(ST_Collect("geom"), 3)');
