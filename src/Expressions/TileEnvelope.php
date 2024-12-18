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
            'zoom' => $this->cast($grammar, $this->zoom),
            'x' => $this->cast($grammar, $this->x),
            'y' => $this->cast($grammar, $this->y),
            'bounds' => $this->bounds !== null
                ? $this->stringize($grammar, $this->bounds)
                : null,
            'margin' => $this->cast($grammar, $this->margin),
        ]);

        return "ST_TileEnvelope($params)";
    }
}
