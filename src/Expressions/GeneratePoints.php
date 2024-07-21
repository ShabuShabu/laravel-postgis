<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_GeneratePoints.html
 */
readonly class GeneratePoints implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private int $points,
        private int $seed = 0,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $points = $this->stringize($grammar, $this->asNumber($this->points));
        $seed = $this->stringize($grammar, $this->asNumber($this->seed));

        return "ST_GeneratePoints($geom, $points, $seed)";
    }
}
