<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Helpers\JsonBuildObject;

it('builds a json object')
    ->expect(new JsonBuildObject([
        'type' => 'collection',
        'features' => new As\GeoJSON('m.*'),
    ]))
    ->toBeExpression("json_build_object('type', 'collection', 'features', ST_AsGeoJSON(\"m\".*, 9, 8))");
