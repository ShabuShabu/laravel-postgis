<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_DumpPoints.html
 */
readonly class DumpPoints implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private ?string $extract = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        if ($this->extract === null) {
            return "ST_DumpPoints($geom)";
        }

        $extract = $this->stringize($grammar, $this->extract);

        return "(ST_DumpPoints($geom)).$extract";
    }
}
