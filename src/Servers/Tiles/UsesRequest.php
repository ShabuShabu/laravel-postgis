<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles;

use Illuminate\Http\Request;

trait UsesRequest
{
    protected ?Request $request = null;

    public function request(Request $request): static
    {
        $this->request = $request;

        return $this;
    }
}
