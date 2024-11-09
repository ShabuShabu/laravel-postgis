<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As\GeoJSON;
use ShabuShabu\PostGIS\Expressions\Helpers\JsonAgg;

it('collects to json')
    ->expect(new JsonAgg('col'))
    ->toBeExpression('json_agg("col")');

it('collects to json from an expression')
    ->expect(new JsonAgg(new GeoJSON('m.*', null, null)))
    ->toBeExpression('json_agg(ST_AsGeoJSON("m".*))');
