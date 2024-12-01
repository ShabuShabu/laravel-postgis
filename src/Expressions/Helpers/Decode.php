<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Enums\Format;

readonly class Decode implements Expression
{
    use Stringable;

    public function __construct(
        private string | Expression $column,
        private Format $format = Format::hex,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $column = $this->stringize($grammar, $this->column);

        return "decode($column, '{$this->format->value}')";
    }
}
