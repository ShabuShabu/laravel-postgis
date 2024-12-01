<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Casts;

use Brick\Geo\Exception\GeometryIOException;
use Brick\Geo\IO\EWKBReader;
use Brick\Geo\IO\EWKBWriter;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use RuntimeException;

class Geometry implements CastsAttributes
{
    public function __construct(
        protected string $class
    ) {}

    /**
     * @throws GeometryIOException
     */
    public function get(mixed $model, string $key, mixed $value, array $attributes): ?\Brick\Geo\Geometry
    {
        if (! class_exists($this->class)) {
            throw new RuntimeException("Geometry class $this->class does not exist");
        }

        return ! $value instanceof $this->class
            ? (new EWKBReader)->read(hex2bin($value))
            : $value;
    }

    /**
     * @throws GeometryIOException
     */
    public function set(mixed $model, string $key, mixed $value, array $attributes): array
    {
        return [
            $key => $value instanceof \Brick\Geo\Geometry
                ? bin2hex((new EWKBWriter)->write($value))
                : $value,
        ];
    }

    public static function using(string $type): string
    {
        return static::class . ':' . $type;
    }
}
