<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Servers\Features\Contracts\Collectable;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsFeatureCollection;
use Tpetry\QueryExpressions\Language\Alias;

class GetFeatureCollection implements GetsFeatureCollection
{
    public function __invoke(Collectable $collection): LazyCollection
    {
        return DB::query()
            ->select(new Alias(new As\GeoJSON('t.*', null, null), 'geojson'))
            ->from($collection->query(), 't')
            ->cursor();
    }
}
