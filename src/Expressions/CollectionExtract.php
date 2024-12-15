<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;
use ShabuShabu\PostGIS\Expressions\Enums\Type;

/**
 * @see https://postgis.net/docs/ST_CollectionExtract.html
 */
readonly class CollectionExtract implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $geom,
        private Type $type = Type::linestring,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $geom = $this->stringize($grammar, $this->geom);

        return "ST_CollectionExtract($geom, {$this->type->value})";
    }
}
