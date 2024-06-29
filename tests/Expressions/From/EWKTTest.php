<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\From;
use Tpetry\QueryExpressions\Value\Value;

it('gets a geom from extended well-known text')
    ->expect(new From\EWKT('ewkt'))
    ->toBeExpression('ST_GeomFromEWKT("ewkt")');

it('gets a geom from extended well-known text expression')
    ->expect(new From\EWKT(new Value('SRID=4269;LINESTRING(-71.160281 42.258729,-71.160837 42.259113,-71.161144 42.25932)')))
    ->toBeExpression("ST_GeomFromEWKT('SRID=4269;LINESTRING(-71.160281 42.258729,-71.160837 42.259113,-71.161144 42.25932)')");
