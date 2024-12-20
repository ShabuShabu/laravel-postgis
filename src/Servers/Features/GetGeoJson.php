<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Servers\Features\Contracts\Geomable;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsGeoJson;
use Tpetry\QueryExpressions\Language\Alias;

class GetGeoJson implements GetsGeoJson
{
    public function __invoke(Geomable $model): string
    {
        $select = collect($model->geoJsonColumns())->map(
            fn (string | Expression $alias, string | int $column) => match (true) {
                $alias instanceof Expression => $alias,
                is_int($column) && is_string($alias) => new Alias($alias, Str::studly($alias)),
                default => new Alias($column, $alias),
            }
        )->values()->all();

        return DB::query()
            ->select(new Alias(new As\GeoJSON('g.*', null, null), 'geojson'))
            ->from(
                call_user_func([get_class($model), 'query'])
                    ->select(['geom', ...$select])
                    ->where('id', $model->getKey()),
                'g'
            )
            ->value('geojson');
    }
}
