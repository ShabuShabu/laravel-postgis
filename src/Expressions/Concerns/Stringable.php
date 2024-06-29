<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Concerns;

use Illuminate\Contracts\Database\Query\Expression;
use Tpetry\QueryExpressions\Concerns\StringizeExpression;
use Tpetry\QueryExpressions\Value\Number;

trait Stringable
{
    use StringizeExpression;

    protected function asNumber(null | string | int | float | Expression $value): null | string | Expression
    {
        if (is_null($value)) {
            return null;
        }

        return is_int($value) || is_float($value)
            ? new Number($value)
            : $value;
    }

    protected function asBool(bool $value): string
    {
        return match ($value) {
            true => 'true',
            false => 'false',
        };
    }
}
