<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Models\DisputedArea;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;

use function ShabuShabu\PostGIS\query;

class DisputedAreas extends Importer implements NeedsRelations
{
    public function builder(): Builder
    {
        return query(config('postgis.models.disputed_area'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.disputed_areas');
    }

    public function sourceId(object $record): string
    {
        return md5($record->ne_id);
    }

    public function mappings(): array
    {
        return [
            'geom' => 'geom',
            'name' => 'name_long',
            'slug' => fn (object $record) => Str::slug($record->name_long),
        ];
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof DisputedArea) {
            return;
        }

        $this->addContinents($model);
        $this->addCountries($model);
        $this->addOceans($model);
        $this->addSeas($model);
        $this->addTimezones($model);
    }

    protected function addContinents(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.continent'),
            config('postgis.models.disputed_area'),
        );

        $model->continents()->toggle($ids);
    }

    protected function addCountries(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.country'),
            config('postgis.models.disputed_area'),
        );

        $model->countries()->toggle($ids);
    }

    protected function addOceans(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.ocean'),
            config('postgis.models.disputed_area'),
        );

        $model->oceans()->toggle($ids);
    }

    protected function addSeas(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.sea'),
            config('postgis.models.disputed_area'),
        );

        $model->seas()->toggle($ids);
    }

    protected function addTimezones(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.disputed_area'),
        );

        $model->timezones()->toggle($ids);
    }
}
