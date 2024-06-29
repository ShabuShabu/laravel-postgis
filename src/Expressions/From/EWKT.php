<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\From;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeomFromEWKT.html
 */
readonly class EWKT implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $ewkt,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $ewkt = $this->stringize($grammar, $this->ewkt);

        return "ST_GeomFromEWKT($ewkt)";
    }
}
