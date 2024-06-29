<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\From;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeomFromEWKB.html
 */
readonly class EWKB implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $ewkb,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $ewkb = $this->stringize($grammar, $this->ewkb);

        return "ST_GeomFromEWKB($ewkb)";
    }
}
