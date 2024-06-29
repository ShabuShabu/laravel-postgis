<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_DWithin.html
 */
readonly class DWithin implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom1,
        private string | Expression $geom2,
        private int | float $distance,
        private ?bool $spheroid = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom1 = $this->stringize($grammar, $this->geom1);
        $geom2 = $this->stringize($grammar, $this->geom2);
        $distance = $this->stringize($grammar, $this->asNumber($this->distance));

        if ($this->spheroid === null) {
            return "ST_DWithin($geom1, $geom2, $distance)";
        }

        $spheroid = $this->asBool($this->spheroid);

        // Need to be a geography columns
        return "ST_DWithin($geom1, $geom2, $distance, $spheroid)";
    }
}
