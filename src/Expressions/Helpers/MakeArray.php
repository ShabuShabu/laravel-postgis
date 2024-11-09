<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use Illuminate\Support\Collection;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class MakeArray implements Expression
{
    use Stringable;

    public function __construct(
        private array $values,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        /** @var Collection $values */
        $values = collect($this->values)
            ->reduce(function (Collection $values, string | Expression $expression) use ($grammar) {
                return $values->push($this->stringize($grammar, $expression));
            }, collect());

        return "array [{$values->implode(', ')}]";
    }
}
