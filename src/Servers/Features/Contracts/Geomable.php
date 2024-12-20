<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Contracts;

interface Geomable
{
    public function getKey();

    public function geoJsonColumns(): array;
}
