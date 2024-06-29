<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Contracts\ByoQuery;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Sea;

use function ShabuShabu\PostGIS\query;

class Seas extends Importer implements ByoQuery, NeedsRelations
{
    protected array $stopWords = ['of', 'the', 'and', 'or', 'de', 'la', 'off'];

    public function builder(): Builder
    {
        return query(config('postgis.models.sea'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.seas');
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
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.ocean'),
            config('postgis.models.sea'),
        );

        $model->oceans()->toggle($ids);
    }

    protected function addTimezones(Sea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.sea'),
        );

        $model->timezones()->toggle($ids);
    }
}
