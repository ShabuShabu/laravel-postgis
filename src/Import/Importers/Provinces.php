<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Province;

use function ShabuShabu\PostGIS\query;

class Provinces extends Importer implements NeedsRelations
{
    public function builder(): Builder
    {
        return query(config('postgis.models.province'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.provinces');
    }

    public function sourceId(object $record): string
    {
        return md5($record->name);
    }

    public function mappings(): array
    {
        return [
            'geom' => 'geom',
            'name' => 'name',
            'iso3166_2' => 'iso_3166_2',
            'slug' => fn (object $record) => Str::slug($record->name),
            'country_id' => fn (object $record) => Cache::remember(
                'import:countries:' . ($code = $record->adm0_a3),
                now()->addMinutes(5),
                static fn () => query(config('postgis.models.country'))
                    ->where('iso3166_1a3', $code)
                    ->firstOrFail()
                    ->getKey()
            ),
        ];
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof Province) {
            return;
        }

        $this->addContinents($model);
        $this->addOceans($model);
        $this->addSeas($model);
        $this->addTimezones($model);
    }

    protected function addContinents(Province $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.continent'),
            config('postgis.models.province'),
        );

        $model->continents()->toggle($ids);
    }

    protected function addOceans(Province $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.ocean'),
            config('postgis.models.province'),
        );

        $model->oceans()->toggle($ids);
    }

    protected function addSeas(Province $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.sea'),
            config('postgis.models.province'),
        );

        $model->seas()->toggle($ids);
    }

    protected function addTimezones(Province $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.province'),
        );

        $model->timezones()->toggle($ids);
    }
}
