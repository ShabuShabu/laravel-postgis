<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class JsonAgg implements Expression
{
    use Stringable;

    public function __construct(
        private string | Expression $column,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);

        return "json_agg($column)";
    }
}
