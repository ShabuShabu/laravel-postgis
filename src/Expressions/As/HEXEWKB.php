<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;
use ShabuShabu\PostGIS\Expressions\Enums\Endian;

/**
 * @see https://postgis.net/docs/ST_AsHEXEWKB.html
 */
readonly class HEXEWKB implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private Endian $endian = Endian::little,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        return "ST_AsHEXEWKB($geom, '{$this->endian->value}')";
    }
}
