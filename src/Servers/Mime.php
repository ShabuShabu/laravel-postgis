<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers;

enum Mime: string
{
    case GEOJSON = 'application/geo+json';
    case MVT = 'application/vnd.mapbox-vector-tile';
}
