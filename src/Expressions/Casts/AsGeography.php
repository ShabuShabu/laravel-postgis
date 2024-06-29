<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Casts;

readonly class AsGeography extends Cast
{
    protected function as(): string
    {
        return 'geography';
    }
}
