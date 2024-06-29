<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Centroid.html
 */
readonly class Centroid implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private ?bool $spheroid = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        if ($this->spheroid === null) {
            return "ST_Centroid($geom)";
        }

        $spheroid = $this->asBool($this->spheroid);

        // Needs to be a geography column
        return "ST_Centroid($geom, $spheroid)";
    }
}
