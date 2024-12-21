<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles\Contracts;

use Illuminate\Support\Collection;

interface GetsMVTStream
{
    public function __invoke(Collection $sources, int $z, int $x, int $y): mixed;
}
