<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Position;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_XMin.html
 */
readonly class MinLongitude implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $column,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);

        return "ST_XMin($column)";
    }
}
