<?php

declare(strict_types=1);

use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Assert;
use ShabuShabu\PostGIS\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

expect()->extend('toBeExpression', function (string $expected) {
    Assert::assertSame($expected, $this->value->getValue(grammar()));

    return $this;
});

function grammar(): Grammar
{
    return DB::connection()->getQueryGrammar();
}
