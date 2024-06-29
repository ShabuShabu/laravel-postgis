<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Enums;

enum Endian: string
{
    case little = 'NDR';
    case big = 'XDR';
}
