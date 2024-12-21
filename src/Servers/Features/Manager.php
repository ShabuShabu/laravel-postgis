<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features;

use BackedEnum;
use RuntimeException;
use ShabuShabu\PostGIS\Servers\Features\Contracts\Collectable;

class Manager
{
    protected array $collections = [];

    public function has(BackedEnum | string $name): bool
    {
        return array_key_exists($this->key($name), $this->collections);
    }

    public function get(BackedEnum | string $name): Collectable
    {
        $key = $this->key($name);

        return $this->collections[$key] ?? throw new RuntimeException("Invalid feature collection: $key");
    }

    public function addCollection(Collectable $collection): self
    {
        $this->collections[$this->key($collection->name())] = $collection;

        return $this;
    }

    protected function key(BackedEnum | string $name): string
    {
        return $name instanceof BackedEnum ? $name->value : $name;
    }
}
