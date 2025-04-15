<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Azimuth.html
 */
readonly class Azimuth implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $origin,
        private string | Expression $target,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $origin = $this->stringize($grammar, $this->origin);
        $target = $this->stringize($grammar, $this->target);

        return "ST_Azimuth($origin, $target)";
    }
}
