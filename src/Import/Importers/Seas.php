<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use App\Models\Sea;
use App\Models\Ocean;
use App\Models\Timezone;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Support\Expressions\GIS\Dump;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\PostGIS\Import\Importer;
use Tpetry\QueryExpressions\Value\Value;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Import\Contracts\ByoQuery;
use App\Support\Expressions\GIS\Intersects;
use App\Services\Import\Contracts\NeedsRelations;

class Seas extends Importer implements ByoQuery, NeedsRelations
{
    protected array $stopWords = ['of', 'the', 'and', 'or', 'de', 'la', 'off'];

    public function builder(): Builder
    {
        return Sea::query();
    }

    protected function sourceLocation(): string
    {
        return storage_path('gis/World_Seas_IHO_v3.zip');
    }

    public function sourceId(object $record): string
    {
        return md5($record->name);
    }

    public function mappings(): array
    {
        return [
            'name' => 'name',
            'geom' => 'geom',
            'slug' => fn (object $record) => Str::slug(str_replace($this->stopWords, '', $record->name)),
        ];
    }

    public function tempQuery(string $name): \Illuminate\Database\Query\Builder
    {
        return DB::table($name)
            ->select(['*'])
            ->where('name', 'not like', '% Ocean')
            ->orderBy('name');
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof Sea) {
            return;
        }

        $this->addOceans($model);
        $this->addTimezones($model);
    }

    protected function addOceans(Sea $model): void
    {
        $ids = Ocean::query()
            ->distinct()
            ->select('oceans.id')
            ->withExpression(
                'se',
                Sea::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('se', new Intersects('oceans.geom', 'se.geom'), '=', new Value(true))
            ->pluck('id');

        $model->oceans()->toggle($ids);
    }

    protected function addTimezones(Sea $model): void
    {
        $ids = Timezone::query()
            ->distinct()
            ->select('timezones.id')
            ->withExpression(
                'se',
                Sea::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('se', new Intersects('timezones.geom', 'se.geom'), '=', new Value(true))
            ->pluck('id');

        $model->timezones()->toggle($ids);
    }
}
