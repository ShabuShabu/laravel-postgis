<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Position\MakeEnvelope;

it('makes an envelope')
    ->expect(new MakeEnvelope(2, 3, 4, 5, 4326))
    ->toBeExpression('ST_MakeEnvelope(2, 3, 4, 5, 4326)');
