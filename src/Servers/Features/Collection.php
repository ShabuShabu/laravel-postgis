<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features;

use Illuminate\Http\Request;
use ShabuShabu\PostGIS\Servers\Features\Contracts\Collectable;

abstract class Collection implements Collectable
{
    protected ?Request $request = null;

    public function request(Request $request): static
    {
        $this->request = $request;

        return $this;
    }
}
