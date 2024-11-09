<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Casts;

readonly class AsJson extends Cast
{
    protected function as(): string
    {
        return 'jsonb';
    }
}
