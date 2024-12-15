<?php

/** @noinspection StaticClosureCanBeUsedInspection */

declare(strict_types=1);

use Brick\Geo\Point;
use ShabuShabu\PostGIS\Tests\App\Models\User;

it('casts a model attribute to a brick geometry', function () {
    $user = User::factory()->create();

    expect($user->geom)
        ->toBeInstanceOf(Point::class)
        ->x()->toBe(75.95707224036518)
        ->y()->toBe(30.85035985617018);

    $user->geom = Point::xy(75.93305977107549, 17.62541518363502, 4326);
    $user->save();

    expect($user->refresh()->geom)
        ->toBeInstanceOf(Point::class)
        ->x()->toBe(75.93305977107549)
        ->y()->toBe(17.62541518363502);
});

it('does not cast a null value', function () {
    $user = User::factory()->create(['geom' => null]);

    expect($user->geom)->toBeNull();
});
