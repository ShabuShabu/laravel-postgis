<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Simplify.html
 */
readonly class Simplify implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int | float $tolerance,
        private bool $preserve = false
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $tolerance = $this->stringize($grammar, $this->asNumber($this->tolerance));
        $preserve = $this->asBool($this->preserve);

        return "ST_Simplify($geom, $tolerance, $preserve)";
    }
}
