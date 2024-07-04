<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import;

use Illuminate\Support\Collection;
use ShabuShabu\PostGIS\Actions\Contracts\ImportsShapefile;
use ShabuShabu\PostGIS\Expressions\Dump;
use ShabuShabu\PostGIS\Expressions\Intersects;
use ShabuShabu\PostGIS\Import\Contracts\Schema;
use Tpetry\QueryExpressions\Value\Value;

use function ShabuShabu\PostGIS\query;

abstract class Importer implements Schema
{
    final public function __construct(
        protected ?string $path = null
    ) {}

    abstract protected function sourceLocation(): string;

    public function shapefileLocation(): string
    {
        return $this->path ?? $this->sourceLocation();
    }

    public static function import(?string $path = null): ?Result
    {
        return app(ImportsShapefile::class)(new static($path));
    }

    protected function ids(mixed $id, string $queryModel, string $geoModel): Collection
    {
        $table = (new $queryModel())->getTable();

        return query($queryModel)
            ->distinct()
            ->select("$table.id")
            ->withExpression(
                'geo',
                query($geoModel)
                    ->select(['id', 'name', new Dump('geom', 'geom')])
                    ->where('id', $id)
            )
            ->join('geo', new Intersects("$table.geom", 'geo.geom'), '=', new Value(true))
            ->pluck('id');
    }
}
