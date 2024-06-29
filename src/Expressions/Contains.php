<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Contains.html
 */
readonly class Contains implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom1,
        private string | Expression $geom2,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom1 = $this->stringize($grammar, $this->geom1);
        $geom2 = $this->stringize($grammar, $this->geom2);

        return "ST_Contains($geom1, $geom2)";
    }
}
