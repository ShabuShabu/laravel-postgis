<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles;

use BackedEnum;
use Illuminate\Http\Request;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\Sourceable;

abstract class Source implements Sourceable
{
    protected ?Request $request = null;

    public function request(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    public function layer(): string
    {
        $name = $this->name();

        return $name instanceof BackedEnum
            ? $name->value
            : $name;
    }

    public function geomIntersectsField(): string
    {
        return 'geom';
    }
}
