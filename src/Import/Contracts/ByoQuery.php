<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Contracts;

use Illuminate\Database\Query\Builder;

interface ByoQuery
{
    public function tempQuery(string $name): Builder;
}
