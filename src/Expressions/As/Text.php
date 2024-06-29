<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsText.html
 */
readonly class Text implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $maxDigits = 15,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $maxDigits = max($this->maxDigits, 0);

        return "ST_AsText($geom, $maxDigits)";
    }
}
