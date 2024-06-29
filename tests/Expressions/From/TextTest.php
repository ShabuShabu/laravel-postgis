<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\From;
use Tpetry\QueryExpressions\Value\Value;

it('gets a geom from well-known text')
    ->expect(new From\Text('wkt'))
    ->toBeExpression('ST_GeomFromText("wkt")');

it('gets a geom from well-known text with an srid')
    ->expect(new From\Text('wkt', 4326))
    ->toBeExpression('ST_GeomFromText("wkt", 4326)');

it('gets a geom from well-known text expression')
    ->expect(new From\Text(new Value('LINESTRING(-71.160281 42.258729,-71.160837 42.259113,-71.161144 42.25932)')))
    ->toBeExpression("ST_GeomFromText('LINESTRING(-71.160281 42.258729,-71.160837 42.259113,-71.161144 42.25932)')");
