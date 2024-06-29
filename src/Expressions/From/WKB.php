<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\From;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeomFromWKB.html
 */
readonly class WKB implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $wkb,
        private ?int $srid = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $wkb = $this->stringize($grammar, $this->wkb);

        if ($this->srid === null) {
            return "ST_GeomFromWKB($wkb)";
        }

        $srid = $this->stringize($grammar, $this->asNumber($this->srid));

        return "ST_GeomFromWKB($wkb, $srid)";
    }
}
