<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\As\MVT;
use ShabuShabu\PostGIS\Expressions\Helpers\StringAgg;

it('aggregates strings')
    ->expect(new StringAgg('col'))
    ->toBeExpression('string_agg("col", \'\')');

it('aggregates strings from an expression')
    ->expect(new StringAgg(new MVT('mvtgeom.*'), '_'))
    ->toBeExpression('string_agg(ST_AsMVT("mvtgeom".*, \'default\'), \'_\')');
