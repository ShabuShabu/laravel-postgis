<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Concerns;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
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

    protected function cast(Grammar $grammar, float | int | string | null | bool $value): ?string
    {
        return match (true) {
            is_float($value),
            is_int($value) => (string) $value,
            is_null($value) => null,
            is_string($value) => $grammar->escape($value),
            is_bool($value) => $this->asBool($value),
        };
    }

    protected function toParams(array $params): string
    {
        return collect($params)
            ->filter(fn (mixed $value) => ! is_null($value))
            ->map(fn (string $value, string $key) => "$key => $value")
            ->implode(', ');
    }
}
