<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Tests\App\Models;

use Brick\Geo\Point;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\PostGIS\Casts\Geometry;
use ShabuShabu\PostGIS\Tests\Database\Factories\UserFactory;

/**
 * @property Point $geom
 */
class User extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'geom',
    ];

    protected function casts(): array
    {
        return [
            'geom' => Geometry::using(Point::class),
        ];
    }

    protected static function newFactory(): UserFactory
    {
        return new UserFactory;
    }
}
