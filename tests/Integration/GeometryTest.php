<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Brick\Geo\Polygon;
use ShabuShabu\PostGIS\Geometry;

it('integrates brick/geo', function () {
    expect(app(Geometry::class))
        ->toBeInstanceOf(Geometry::class)
        ->area(Polygon::fromText('POLYGON ((0 0, 0 3, 3 3, 0 0))'))
        ->toBe(4.5);
});
