<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Contracts;

use Illuminate\Support\LazyCollection;

interface GetsFeatureCollection
{
    public function __invoke(Collectable $collection): LazyCollection;
}
