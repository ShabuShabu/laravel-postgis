<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_Transform.html
 */
readonly class Transform implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $srid,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $srid = $this->stringize($grammar, $this->asNumber($this->srid));

        return "ST_Transform($geom, $srid)";
    }
}
