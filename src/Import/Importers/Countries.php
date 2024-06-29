<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use App\Models\Sea;
use App\Models\Ocean;
use App\Models\Country;
use App\Models\Timezone;
use App\Models\Continent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Support\Expressions\GIS\Dump;
use App\Support\Expressions\GIS\Multi;
use App\Support\Expressions\GIS\Union;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\PostGIS\Import\Importer;
use Tpetry\QueryExpressions\Value\Value;
use Illuminate\Database\Eloquent\Builder;
use App\Services\Import\Contracts\ByoQuery;
use App\Support\Expressions\GIS\Intersects;
use Tpetry\QueryExpressions\Language\Alias;
use App\Services\Import\Contracts\NeedsRelations;

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
        'Somalia'
    ];

    public function builder(): Builder
    {
        return Country::query();
    }

    protected function sourceLocation(): string
    {
        return storage_path('gis/ne_10m_admin_0_countries.zip');
    }

    public function sourceId(object $record): string
    {
        return md5($record->name_long);
    }

    public function mappings(): array
    {
        $getName = fn (object $record) => $record->name_long === 'Heard I. and McDonald Islands'
            ? 'Heard Island and McDonald Islands'
            : $record->name_long;

        return [
            'geom'        => 'geom',
            'name'        => $getName,
            'slug'        => fn (object $record) => Str::slug(str_replace(['and', 'of', 'the'], '', $getName($record))),
            'iso3166_1a2' => fn (object $record) => match (true) {
                $record->name_long === 'Taiwan' => 'TW',
                $record->name_long === 'Norway' => 'NO',
                $record->name_long === 'France' => 'FR',
                $record->name_long === 'Kosovo' => 'XK',
                $record->iso_a2 === '-99'       => null,
                default                         => $record->iso_a2,
            },
            'iso3166_1a3' => fn (object $record) => match (true) {
                $record->name_long === 'Norway' => 'NOR',
                $record->name_long === 'France' => 'FRA',
                $record->name_long === 'Kosovo' => 'XKX',
                $record->iso_a3 === '-99'       => null,
                default                         => $record->iso_a3,
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
                    ->take(1)
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
        $ids = Continent::query()
            ->distinct()
            ->select('continents.id')
            ->withExpression(
                'co',
                Country::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('co', new Intersects('continents.geom', 'co.geom'), '=', new Value(true))
            ->pluck('id');

        $model->continents()->toggle($ids);
    }

    protected function addOceans(Country $model): void
    {
        $ids = Ocean::query()
            ->distinct()
            ->select('oceans.id')
            ->withExpression(
                'co',
                Country::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('co', new Intersects('oceans.geom', 'co.geom'), '=', new Value(true))
            ->pluck('id');

        $model->oceans()->toggle($ids);
    }

    protected function addSeas(Country $model): void
    {
        $ids = Sea::query()
            ->distinct()
            ->select('seas.id')
            ->withExpression(
                'co',
                Country::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('co', new Intersects('seas.geom', 'co.geom'), '=', new Value(true))
            ->pluck('id');

        $model->seas()->toggle($ids);
    }

    protected function addTimezones(Country $model): void
    {
        $ids = Timezone::query()
            ->distinct()
            ->select('timezones.id')
            ->withExpression(
                'co',
                Country::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('co', new Intersects('timezones.geom', 'co.geom'), '=', new Value(true))
            ->pluck('id');

        $model->timezones()->toggle($ids);
    }
}
