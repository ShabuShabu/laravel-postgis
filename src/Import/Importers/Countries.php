<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Expressions\Multi;
use ShabuShabu\PostGIS\Expressions\Union;
use ShabuShabu\PostGIS\Import\Contracts\ByoQuery;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Country;
use Tpetry\QueryExpressions\Language\Alias;

use function ShabuShabu\PostGIS\query;

class Countries extends Importer implements ByoQuery, NeedsRelations
{
    protected array $skip = [
        'Akrotiri',
        'Ashmore and Cartier Islands',
        'Baikonur Cosmodrome',
        'Bajo Nuevo Bank (Petrel Islands)',
        'Bir Tawil',
        'Brazilian Island',
        'Clipperton Island',
        'Dhekelia',
        'Indian Ocean Territories',
        'Scarborough Reef',
        'Serranilla Bank',
        'Siachen Glacier',
        'Southern Patagonian Ice Field',
        'Spratly Islands',
        'US Naval Base Guantanamo Bay',
        'Coral Sea Islands',
        'Australia',
        'Northern Cyprus',
        'Cyprus U.N. Buffer Zone',
        'Cyprus',
        'Somaliland',
        'Somalia',
    ];

    public function builder(): Builder
    {
        return query(config('postgis.models.country'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.countries');
    }

    public function sourceId(object $record): string
    {
        return md5($record->name_long);
    }

    public function mappings(): array
    {
        $getName = static fn (object $record) => $record->name_long === 'Heard I. and McDonald Islands'
            ? 'Heard Island and McDonald Islands'
            : $record->name_long;

        return [
            'geom' => 'geom',
            'name' => $getName,
            'slug' => fn (object $record) => Str::slug(str_replace(['and', 'of', 'the'], '', $getName($record))),
            'iso3166_1a2' => fn (object $record) => match (true) {
                $record->name_long === 'Taiwan' => 'TW',
                $record->name_long === 'Norway' => 'NO',
                $record->name_long === 'France' => 'FR',
                $record->name_long === 'Kosovo' => 'XK',
                $record->iso_a2 === '-99' => null,
                default => $record->iso_a2,
            },
            'iso3166_1a3' => fn (object $record) => match (true) {
                $record->name_long === 'Norway' => 'NOR',
                $record->name_long === 'France' => 'FRA',
                $record->name_long === 'Kosovo' => 'XKX',
                $record->iso_a3 === '-99' => null,
                default => $record->iso_a3,
            },
        ];
    }

    protected function combine(string $table, string $main, array $areas): \Illuminate\Database\Query\Builder
    {
        return DB::table($table, 'c')
            ->select([
                'c.name_long',
                'c.iso_a2',
                'c.iso_a3',
                'continent',
                'region_un',
                'geom' => DB::table($table)
                    ->select(new Alias(new Multi(new Union('geom')), 'geom'))
                    ->whereIn('name_long', $areas)
                    ->take(1),
            ])->where('c.name_long', $main);
    }

    public function tempQuery(string $name): \Illuminate\Database\Query\Builder
    {
        return DB::query()
            ->from(
                DB::table($name)
                    ->select(['name_long', 'iso_a2', 'iso_a3', 'continent', 'region_un', 'geom'])
                    ->whereNotIn('name_long', $this->skip)
                    ->union($this->combine($name, 'Australia', ['Coral Sea Islands', 'Australia']))
                    ->union($this->combine($name, 'Somalia', ['Somaliland', 'Somalia']))
                    ->union($this->combine($name, 'Cyprus', ['Northern Cyprus', 'Cyprus U.N. Buffer Zone', 'Cyprus'])),
                't'
            )
            ->orderBy('name_long');
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof Country) {
            return;
        }

        $this->addContinents($model);
        $this->addOceans($model);
        $this->addSeas($model);
        $this->addTimezones($model);
    }

    protected function addContinents(Country $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.continent'),
            config('postgis.models.country'),
        );

        $model->continents()->toggle($ids);
    }

    protected function addOceans(Country $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.ocean'),
            config('postgis.models.country'),
        );

        $model->oceans()->toggle($ids);
    }

    protected function addSeas(Country $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.sea'),
            config('postgis.models.country'),
        );

        $model->seas()->toggle($ids);
    }

    protected function addTimezones(Country $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.country'),
        );

        $model->timezones()->toggle($ids);
    }
}
