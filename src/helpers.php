<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS;

use Illuminate\Database\Eloquent\Builder;
use ShabuShabu\PostGIS\Expressions\Area;
use ShabuShabu\PostGIS\Expressions\Casts\AsGeography;
use ShabuShabu\PostGIS\Expressions\Casts\AsNumeric;
use ShabuShabu\PostGIS\Expressions\Math\Round;
use Tpetry\QueryExpressions\Operator\Arithmetic\Divide;
use Tpetry\QueryExpressions\Value\Value;

function area(string $column = 'geom'): Round
{
    return new Round(
        new AsNumeric(
            new Divide(
                new Area(new AsGeography($column)),
                new Value(1e+6)
            )
        )
    );
}

function query(string $class): Builder
{
    return call_user_func([$class, 'query']);
}
