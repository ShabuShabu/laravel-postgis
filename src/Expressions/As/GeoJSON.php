<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;
use ShabuShabu\PostGIS\Expressions\Enums\Option;

/**
 * @see https://postgis.net/docs/ST_AsGeoJSON.html
 */
readonly class GeoJSON implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $maxDigits = 9,
        private Option $option = Option::shortCRSNot4326,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $maxDigits = max($this->maxDigits, 0);

        return "ST_AsGeoJSON($geom, $maxDigits, {$this->option->value})";
    }
}
