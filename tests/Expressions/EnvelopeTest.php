<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Collect;
use ShabuShabu\PostGIS\Expressions\Envelope;

it('calculates the envelope for a geom')
    ->expect(new Envelope('geom'))
    ->toBeExpression('ST_Envelope("geom")');

it('calculates the envelope for an expression')
    ->expect(new Envelope(new Collect('geom')))
    ->toBeExpression('ST_Envelope(ST_Collect("geom"))');
