<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsGeobuf.html
 */
readonly class Geobuf implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $row,
        private ?string $geomName = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $row = $this->stringize($grammar, $this->row);

        if ($this->geomName === null) {
            return "ST_AsGeobuf($row)";
        }

        $geomName = $grammar->escape($this->geomName);

        return "ST_AsGeobuf($row, $geomName)";
    }
}
