<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Illuminate\Support\Collection;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use Tpetry\QueryExpressions\Value\Value;

readonly class JsonBuildObject implements Expression
{
    use Stringable;

    public function __construct(
        private array $values,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        /** @var Collection $values */
        $values = collect($this->values)
            ->reduce(function (Collection $values, Expression | string $expression, string $key) use ($grammar) {
                $values->push($this->stringize($grammar, new Value($key)));
                $values->push($this->stringize($grammar, is_string($expression) ? new Value($expression) : $expression));

                return $values;
            }, collect());

        return "json_build_object({$values->implode(', ')})";
    }
}
