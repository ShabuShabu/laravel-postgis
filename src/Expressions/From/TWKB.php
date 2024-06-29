<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\From;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeomFromTWKB.html
 */
readonly class TWKB implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $twkb,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $twkb = $this->stringize($grammar, $this->twkb);

        return "ST_GeomFromTWKB($twkb)";
    }
}
