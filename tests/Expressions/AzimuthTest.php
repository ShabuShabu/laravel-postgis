<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Azimuth;
use ShabuShabu\PostGIS\Expressions\Collect;

it('calculates the bearing')
    ->expect(new Azimuth('origin', 'target'))
    ->toBeExpression('ST_Azimuth("origin", "target")');

it('calculates the geometry expression bearing')
    ->expect(new Azimuth(new Collect('origin'), new Collect('target')))
    ->toBeExpression('ST_Azimuth(ST_Collect("origin"), ST_Collect("target"))');
