<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use App\Models\Ocean;
use App\Models\Timezone;
use Illuminate\Support\Str;
use App\Support\Expressions\GIS\Dump;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\PostGIS\Import\Importer;
use Tpetry\QueryExpressions\Value\Value;
use Illuminate\Database\Eloquent\Builder;
use App\Support\Expressions\GIS\Intersects;
use App\Services\Import\Contracts\NeedsRelations;

class Oceans extends Importer implements NeedsRelations
{
    public function builder(): Builder
    {
        return Ocean::query();
    }

    protected function sourceLocation(): string
    {
        return storage_path('gis/GOaS_v1_20211214.zip');
    }

    public function sourceId(object $record): string
    {
        return md5($record->name);
    }

    public function mappings(): array
    {
        $getName = static fn (object $record) => $record->name === 'Mediterranean Region'
            ? 'Mediterranean Sea'
            : $record->name;

        return [
            'geom' => 'geom',
            'slug' => fn (object $record) => Str::slug(str_replace('and', '', $getName($record))),
            'name' => $getName,
        ];
    }

    public function addRelationships(Model $model, object $record): void
    {
        if ($model instanceof Ocean) {
            $this->addTimezones($model);
        }
    }

    protected function addTimezones(Ocean $model): void
    {
        $ids = Timezone::query()
            ->distinct()
            ->select('timezones.id')
            ->withExpression(
                'oc',
                Ocean::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('oc', new Intersects('timezones.geom', 'oc.geom'), '=', new Value(true))
            ->pluck('id');

        $model->timezones()->toggle($ids);
    }
}
