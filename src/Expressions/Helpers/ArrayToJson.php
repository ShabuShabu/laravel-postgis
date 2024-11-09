<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Helpers;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;

readonly class ArrayToJson implements Expression
{
    use Stringable;

    public function __construct(
        private MakeArray $array,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $array = $this->stringize($grammar, $this->array);

        return "array_to_json($array)";
    }
}
