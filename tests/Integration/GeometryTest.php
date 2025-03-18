<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Brick\Geo\Engine\PdoEngine;
use Brick\Geo\Polygon;

it('integrates brick/geo', function () {
    expect(app(PdoEngine::class))
        ->toBeInstanceOf(PdoEngine::class)
        ->area(Polygon::fromText('POLYGON ((0 0, 0 3, 3 3, 0 0))'))
        ->toBe(4.5);
});
