<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Enums;

enum Type: int
{
    case point = 1;
    case linestring = 2;
    case polygon = 3;
}
