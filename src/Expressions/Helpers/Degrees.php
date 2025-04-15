<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class Degrees implements Expression
{
    use Stringable;

    public function __construct(
        private float | string | Expression $value,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->asNumber($this->value));

        return "degrees($value)";
    }
}
