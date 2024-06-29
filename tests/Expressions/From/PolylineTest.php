<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\From;
use Tpetry\QueryExpressions\Value\Value;

it('gets a geom from a polyline')
    ->expect(new From\Polyline('line'))
    ->toBeExpression('ST_LineFromEncodedPolyline("line", 5)');

it('gets a geom from a polyline with a given precision')
    ->expect(new From\Polyline('line', 7))
    ->toBeExpression('ST_LineFromEncodedPolyline("line", 7)');

it('gets a geom from a polyline expression')
    ->expect(new From\Polyline(new Value('_p~iF~ps|U_ulLnnqC_mqNvxq`@')))
    ->toBeExpression("ST_LineFromEncodedPolyline('_p~iF~ps|U_ulLnnqC_mqNvxq`@', 5)");
