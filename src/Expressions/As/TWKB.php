<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsTWKB.html
 */
readonly class TWKB implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $precision = 0,
        private int $precisionZ = 0,
        private int $precisionM = 0,
        private bool $withSizes = false,
        private bool $withBoxes = false,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        $precision = max($this->precision, 0);
        $precisionZ = max($this->precisionZ, 0);
        $precisionM = max($this->precisionM, 0);

        $withSizes = $this->asBool($this->withSizes);
        $withBoxes = $this->asBool($this->withBoxes);

        return "ST_AsTWKB($geom, $precision, $precisionZ, $precisionM, $withSizes, $withBoxes)";
    }
}
