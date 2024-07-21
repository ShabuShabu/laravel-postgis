<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\GeneratePoints;

it('generates points')
    ->expect(new GeneratePoints('geom', 5, 1996))
    ->toBeExpression('ST_GeneratePoints("geom", 5, 1996)');
