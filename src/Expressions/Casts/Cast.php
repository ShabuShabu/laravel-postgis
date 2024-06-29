<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Casts;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

abstract readonly class Cast implements Expression
{
    use Stringable;

    public function __construct(
        private string | Expression $value,
    ) {}

    abstract protected function as(): string;

    public function getValue(Grammar $grammar): string
    {
        $value = $this->stringize($grammar, $this->value);
        $as = $this->as();

        return "$value::$as";
    }
}
