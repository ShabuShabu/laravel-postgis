<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use RuntimeException;

class Geometry implements CastsAttributes
{
    public function __construct(
        protected string $class,
        protected int $srid = 4326
    ) {}

    public function get(mixed $model, string $key, mixed $value, array $attributes): ?\Brick\Geo\Geometry
    {
        if (! class_exists($this->class)) {
            throw new RuntimeException("Geometry class $this->class does not exist");
        }

        if (! method_exists($this->class, 'fromBinary')) {
            throw new RuntimeException("Geometry class $this->class fromBinary method does not exist");
        }

        return $this->class::fromBinary($value, $this->srid);
    }

    public function set(mixed $model, string $key, mixed $value, array $attributes): array
    {
        return [
            $key => $value instanceof \Brick\Geo\Geometry
                ? $value->asBinary()
                : $value,
        ];
    }

    public static function using(string $type, int $srid = 4326): string
    {
        return static::class . ':' . $type . ',' . $srid;
    }
}
