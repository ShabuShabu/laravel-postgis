<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Ocean;

use function ShabuShabu\PostGIS\query;

class Oceans extends Importer implements NeedsRelations
{
    public function builder(): Builder
    {
        return query(config('postgis.models.ocean'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.oceans');
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
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.ocean'),
        );

        $model->timezones()->toggle($ids);
    }
}
