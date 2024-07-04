<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Contracts\ByoQuery;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Province;

use function ShabuShabu\PostGIS\query;

class Provinces extends Importer implements ByoQuery, NeedsRelations
{
    protected array $slugs = [];

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
        return md5($record->ne_id);
    }

    public function mappings(): array
    {
        return [
            'geom' => 'geom',
            'name' => 'name',
            'iso3166_2' => 'iso_3166_2',
            'type' => fn (object $record) => is_string($record->type_en) ? Str::slug(strtolower($record->type_en)) : null,
            'slug' => $this->uniqueSlug(...),
            'country_id' => fn (object $record) => Cache::remember(
                'import:countries:' . $record->adm0_a3,
                now()->addMinutes(5),
                static fn () => query(config('postgis.models.country'))
                    ->where(
                        fn (Builder $query) => $query
                            ->where('iso3166_1a3', $record->adm0_a3)
                            ->orWhere('iso3166_1a2', $record->iso_a2)
                    )
                    ->first()
                    ?->getKey()
            ),
        ];
    }

    protected function uniqueSlug(object $record): string
    {
        $baseSlug = Str::slug($record->name);
        $slug = $baseSlug;
        $count = 1;

        while (in_array($slug, $this->slugs, true)) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $this->slugs[] = $slug;
        $this->slugs = array_unique($this->slugs);

        return $slug;
    }

    public function tempQuery(string $name): \Illuminate\Database\Query\Builder
    {
        return DB::table($name)
            ->select(['*'])
            ->whereNotNull('name')
            ->orderBy('name');
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof Province) {
            return;
        }

        $this->addOceans($model);
        $this->addSeas($model);
        $this->addTimezones($model);
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
