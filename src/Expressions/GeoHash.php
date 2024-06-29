<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeoHash.html
 */
readonly class GeoHash implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private ?int $maxChars = null
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        if (is_int($this->maxChars)) {
            $maxChars = max($this->maxChars, 0);

            return "ST_GeoHash($geom, $maxChars)";
        }

        return "ST_GeoHash($geom)";
    }
}
