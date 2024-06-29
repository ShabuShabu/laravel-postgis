<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Enums;

enum Option: int
{
    case none = 0;
    case bbox = 1;
    case shortCRS = 2;
    case longCRS = 4;
    case shortCRSNot4326 = 8;
}
