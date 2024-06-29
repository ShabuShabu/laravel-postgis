<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsEncodedPolyline.html
 */
readonly class Polyline implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $precision = 5,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $precision = max($this->precision, 0);

        return "ST_AsEncodedPolyline($geom, $precision)";
    }
}
