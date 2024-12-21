<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles;

use BackedEnum;
use RuntimeException;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\Sourceable;

class SourceManager
{
    protected array $sources = [];

    public function isSource(BackedEnum | string $name): bool
    {
        return array_key_exists($this->key($name), $this->sources);
    }

    public function source(BackedEnum | string $name): Sourceable
    {
        $key = $this->key($name);

        return $this->sources[$key] ?? throw new RuntimeException("Invalid tile source: $key");
    }

    public function addSource(Sourceable $source): self
    {
        $this->sources[$this->key($source->name())] = $source;

        return $this;
    }

    protected function key(BackedEnum | string $name): string
    {
        return $name instanceof BackedEnum ? $name->value : $name;
    }
}
