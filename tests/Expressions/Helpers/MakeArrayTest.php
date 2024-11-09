<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Helpers\MakeArray;
use ShabuShabu\PostGIS\Expressions\Position\MaxLongitude;

it('makes an array')
    ->expect(new MakeArray(['test', new MaxLongitude('bbox')]))
    ->toBeExpression('array ["test", ST_XMax("bbox")]');
