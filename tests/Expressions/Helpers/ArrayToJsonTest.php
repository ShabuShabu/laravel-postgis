<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use ShabuShabu\PostGIS\Expressions\Helpers\ArrayToJson;
use ShabuShabu\PostGIS\Expressions\Helpers\MakeArray;

it('transforms an array to json')
    ->expect(new ArrayToJson(new MakeArray(['test1', 'test2'])))
    ->toBeExpression('array_to_json(array ["test1", "test2"])');
