<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Casts\AsJson;
use Tpetry\QueryExpressions\Value\Value;

it('casts a column to jsonb')
    ->expect(new AsJson('col'))
    ->toBeExpression('"col"::jsonb');

it('casts a column to jsonb expression')
    ->expect(new AsJson(new Value('[{"test": 1}]')))
    ->toBeExpression("'[{\"test\": 1}]'::jsonb");
