<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles\Contracts;

interface GetsMVTStream
{
    public function __invoke(Sourceable $source, int $z, int $x, int $y): mixed;
}
