<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use ShabuShabu\PostGIS\Import\Concerns\NeedsUniqueSlug;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\DisputedArea;

use function ShabuShabu\PostGIS\query;

class DisputedAreas extends Importer implements NeedsRelations
{
    use NeedsUniqueSlug;

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
            'name' => 'brk_name',
            'slug' => fn (object $record) => $this->uniqueSlug($record->brk_name),
            'administered_by_id' => fn (object $record) => Cache::remember(
                'import:disputed:' . $record->adm0_a3,
                now()->addMinutes(5),
                static fn () => query(config('postgis.models.country'))
                    ->where('iso3166_1a3', $record->adm0_a3)
                    ->first()
                    ?->getKey()
            ),
        ];
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof DisputedArea) {
            return;
        }

        $this->addContinents($model);
        $this->addCountries($model);
        $this->addProvinces($model);
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

    protected function addProvinces(DisputedArea $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.province'),
            config('postgis.models.disputed_area'),
        );

        $model->provinces()->toggle($ids);
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
