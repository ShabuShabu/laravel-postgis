<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles\Actions;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Expressions\As;
use ShabuShabu\PostGIS\Expressions\Helpers\StringAgg;
use ShabuShabu\PostGIS\Expressions\Intersects;
use ShabuShabu\PostGIS\Expressions\Position\MakeEnvelope;
use ShabuShabu\PostGIS\Expressions\TileEnvelope;
use ShabuShabu\PostGIS\Expressions\Transform;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\GetsMVTStream;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\Sourceable;
use Tpetry\QueryExpressions\Language\Alias;
use Tpetry\QueryExpressions\Value\Value;

class GetMVTStream implements GetsMVTStream
{
    public function __invoke(Collection $sources, int $z, int $x, int $y): mixed
    {
        if ($this->isInvalidTile($z, $x, $y)) {
            return false;
        }

        $first = $sources->pull(0);

        return DB::query()
            ->select(new Alias(new StringAgg('p.mvt'), 'pbf'))
            ->from(
                $sources->reduce(
                    fn (Builder $builder, Sourceable $source) => $builder->union(
                        $this->query($source, $z, $x, $y)
                    ),
                    $this->query($first, $z, $x, $y)
                ),
                'p'
            )
            ->value('pbf');
    }

    protected function query(Sourceable $source, int $z, int $x, int $y): Builder
    {
        return DB::query()
            ->select(new Alias(new As\MVT('mvtgeom.*', $source->layer()), 'mvt'))
            ->from(
                DB::query()->select([
                    new Alias(
                        new As\MVTGeom(
                            new Transform('t.geom', 3857),
                            new TileEnvelope($z, $x, $y),
                        ),
                        'geom',
                    ),
                    ...array_map(
                        static fn (string $column) => Str::start($column, 't.'),
                        array_values(
                            array_unique(['id', ...$source->columns()])
                        )
                    ),
                ])->from(
                    $source->query()->where(
                        new Intersects($source->geomIntersectsField(), $this->envelope($z, $x, $y)),
                        new Value(true),
                    ),
                    't'
                ),
                'mvtgeom'
            );
    }

    protected function isInvalidTile(int $z, int $x, int $y): bool
    {
        return $x < 0 || $x >= 2 ** $z || $y < 0 || $y >= 2 ** $z;
    }

    /**
     * @see https://github.com/pramsey/minimal-mvt/blob/8b736e342ada89c5c2c9b1c77bfcbcfde7aa8d82/minimal-mvt.py#L64-L81
     */
    protected function envelope(int $z, int $x, int $y): Transform
    {
        $worldMax = 20037508.3427892;
        $worldMin = -1 * $worldMax;

        // in EPSG:3857
        $tileWidth = ($worldMax - $worldMin) / (2 ** $z);

        // Calculate geographic bounds from tile coordinates
        return new Transform(new MakeEnvelope(
            xmin: $worldMin + $tileWidth * $x,
            ymin: $worldMax - $tileWidth * ($y + 1),
            xmax: $worldMin + $tileWidth * ($x + 1),
            ymax: $worldMax - $tileWidth * $y,
            srid: 3857,
        ), 4326);
    }
}
