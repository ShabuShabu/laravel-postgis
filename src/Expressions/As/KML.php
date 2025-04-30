<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsKML.html
 */
readonly class KML implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private ?int $maxDecimalDigits = null,
        private ?string $nPrefix = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $params = $this->toParams([
            $this->stringize($grammar, $this->geom),
            $this->cast($grammar, $this->maxDecimalDigits),
            $this->cast($grammar, $this->nPrefix),
        ]);

        return "ST_AsKML($params)";
    }
}
