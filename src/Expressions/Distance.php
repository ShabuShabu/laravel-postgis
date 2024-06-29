<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Distance.html
 */
readonly class Distance implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom1,
        private string | Expression $geom2,
        private ?bool $spheroid = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom1 = $this->stringize($grammar, $this->geom1);
        $geom2 = $this->stringize($grammar, $this->geom2);

        if ($this->spheroid === null) {
            return "ST_Distance($geom1, $geom2)";
        }

        $spheroid = $this->asBool($this->spheroid);

        // Need to be geography columns
        return "ST_Distance($geom1, $geom2, $spheroid)";
    }
}
