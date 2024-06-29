<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Math;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class Round implements Expression
{
    use Stringable;

    public function __construct(
        private float | string | Expression $source,
        private int $decimals = 0
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $source = $this->stringize($grammar, $this->asNumber($this->source));
        $decimals = max($this->decimals, 0);

        return "round($source, $decimals)";
    }
}
