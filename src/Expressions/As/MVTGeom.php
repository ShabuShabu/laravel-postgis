<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsMVTGeom.html
 */
readonly class MVTGeom implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private string | Expression $bounds,
        private int $extent = 4096,
        private int $buffer = 256,
        private bool $clipGeom = true,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);
        $bounds = $this->stringize($grammar, $this->bounds);
        $extent = $this->cast($grammar, $this->extent);
        $buffer = $this->cast($grammar, $this->buffer);
        $clipGeom = $this->cast($grammar, $this->clipGeom);

        return "ST_AsMVTGeom($geom, $bounds, $extent, $buffer, $clipGeom)";
    }
}
