<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Enums;

enum Format: string
{
    case base64 = 'base64';
    case escape = 'escape';
    case hex = 'hex';
}
