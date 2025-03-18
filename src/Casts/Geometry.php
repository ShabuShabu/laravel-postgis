<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Casts;

use Brick\Geo\Exception\GeometryIoException;
use Brick\Geo\Io\EwkbReader;
use Brick\Geo\Io\EwkbWriter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use RuntimeException;

class Geometry implements CastsAttributes
{
    public function __construct(
        protected string $class
    ) {}

    /**
     * @throws GeometryIoException
     */
    public function get(mixed $model, string $key, mixed $value, array $attributes): ?\Brick\Geo\Geometry
    {
        if (! class_exists($this->class)) {
            throw new RuntimeException("Geometry class $this->class does not exist");
        }

        if ($value instanceof $this->class) {
            return $value;
        }

        return is_string($value)
            ? (new EwkbReader)->read(hex2bin($value))
            : null;
    }

    /**
     * @throws GeometryIoException
     */
    public function set(mixed $model, string $key, mixed $value, array $attributes): array
    {
        return [
            $key => $value instanceof \Brick\Geo\Geometry
                ? bin2hex((new EwkbWriter)->write($value))
                : $value,
        ];
    }

    public static function using(string $type): string
    {
        return static::class . ':' . $type;
    }
}
