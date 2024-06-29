<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\From;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeomFromText.html
 */
readonly class Text implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $wkt,
        private ?int $srid = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $wkt = $this->stringize($grammar, $this->wkt);

        if ($this->srid === null) {
            return "ST_GeomFromText($wkt)";
        }

        $srid = $this->stringize($grammar, $this->asNumber($this->srid));

        return "ST_GeomFromText($wkt, $srid)";
    }
}
