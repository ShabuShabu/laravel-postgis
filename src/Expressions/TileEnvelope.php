<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_TileEnvelope.html
 */
readonly class TileEnvelope implements GisExpression
{
    use Stringable;

    public function __construct(
        private int $zoom,
        private int $x,
        private int $y,
        private null | string | Expression $bounds = null,
        private ?float $margin = null
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $params = $this->toParams([
            $this->cast($grammar, $this->zoom),
            $this->cast($grammar, $this->x),
            $this->cast($grammar, $this->y),
            $this->bounds !== null
                ? $this->stringize($grammar, $this->bounds)
                : null,
            $this->cast($grammar, $this->margin),
        ]);

        return "ST_TileEnvelope($params)";
    }
}
