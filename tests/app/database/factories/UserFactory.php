<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Tests\Database\Factories;

use Brick\Geo\Exception\CoordinateSystemException;
use Brick\Geo\Exception\GeometryIOException;
use Brick\Geo\Exception\InvalidGeometryException;
use Brick\Geo\Exception\UnexpectedGeometryException;
use Brick\Geo\Point;
use Illuminate\Database\Eloquent\Factories\Factory;
use ShabuShabu\PostGIS\Tests\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @throws CoordinateSystemException
     * @throws GeometryIOException
     * @throws UnexpectedGeometryException
     * @throws InvalidGeometryException
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'geom' => Point::fromText('POINT(75.95707224036518 30.85035985617018)', 4326),
        ];
    }
}
