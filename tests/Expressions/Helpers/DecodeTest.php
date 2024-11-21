<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Enums\Format;
use ShabuShabu\PostGIS\Expressions\Helpers\Decode;
use Tpetry\QueryExpressions\Value\Value;

it('decodes a value')
    ->expect(new Decode(new Value('1lkjflskdfjv'), Format::hex))
    ->toBeExpression("decode('1lkjflskdfjv', 'hex')");
