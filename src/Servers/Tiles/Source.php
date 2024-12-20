<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles;

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
}
