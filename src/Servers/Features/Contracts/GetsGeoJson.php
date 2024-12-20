<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Contracts;

interface GetsGeoJson
{
    public function __invoke(Geomable $model): string;
}
