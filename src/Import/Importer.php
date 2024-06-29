<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import;

use ShabuShabu\PostGIS\Import\Contracts\Schema;
use ShabuShabu\PostGIS\Actions\Contracts\ImportsShapefile;

abstract class Importer implements Schema
{
    final public function __construct(
        protected ?string $path = null
    ) {
    }

    abstract protected function sourceLocation(): string;

    public function shapefileLocation(): string
    {
        return $this->path ?? $this->sourceLocation();
    }

    public static function import(?string $path = null): ?Result
    {
        return app(ImportsShapefile::class)(new static($path));
    }
}
