<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Multi.html
 */
readonly class Multi implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        return "ST_Multi($geom)";
    }
}
