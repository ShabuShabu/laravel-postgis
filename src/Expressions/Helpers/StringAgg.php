<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class StringAgg implements Expression
{
    use Stringable;

    public function __construct(
        private string | Expression $column,
        private string $delimiter = '',
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);
        $delimiter = $grammar->escape($this->delimiter);

        return "string_agg($column, $delimiter)";
    }
}
