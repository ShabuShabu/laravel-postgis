<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsFlatGeobuf.html
 */
readonly class FlatGeobuf implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $row,
        private bool $index = false,
        private ?string $geomName = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $row = $this->stringize($grammar, $this->row);
        $index = $this->asBool($this->index);

        if ($this->geomName === null) {
            return "ST_AsFlatGeobuf($row, $index)";
        }

        $geomName = $grammar->escape($this->geomName);

        return "ST_AsFlatGeobuf($row, $index, $geomName)";
    }
}
