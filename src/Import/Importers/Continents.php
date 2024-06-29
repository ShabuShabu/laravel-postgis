<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use App\Models\Timezone;
use App\Models\Continent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Support\Expressions\GIS\Dump;
use Illuminate\Database\Eloquent\Model;
use ShabuShabu\PostGIS\Import\Importer;
use App\Support\Expressions\GIS\SetSRID;
use Tpetry\QueryExpressions\Value\Value;
use Illuminate\Database\Eloquent\Builder;
use App\Support\Expressions\GIS\Transform;
use App\Services\Import\Contracts\ByoQuery;
use App\Support\Expressions\GIS\Intersects;
use Tpetry\QueryExpressions\Language\Alias;
use App\Services\Import\Contracts\NeedsRelations;

class Continents extends Importer implements ByoQuery, NeedsRelations
{
    public function builder(): Builder
    {
        return Continent::query();
    }

    protected function sourceLocation(): string
    {
        return storage_path('gis/World_Continents_-8398826466908339531.zip');
    }

    public function sourceId(object $record): string
    {
        return md5($record->continent);
    }

    public function mappings(): array
    {
        return [
            'geom' => 'geom',
            'name' => 'continent',
            'slug' => fn (object $record) => Str::slug($record->continent),
            'code' => fn (object $record) => match ($record->continent) {
                'Africa'        => 'AF',
                'Antarctica'    => 'AN',
                'Asia'          => 'AS',
                'Australia'     => 'AU',
                'Europe'        => 'EU',
                'North America' => 'NA',
                'Oceania'       => 'OC',
                'South America' => 'SA',
            },
        ];
    }

    public function tempQuery(string $name): \Illuminate\Database\Query\Builder
    {
        return DB::query()
            ->select(['*'])
            ->from(
                DB::table($name)->select([
                    'continent',
                    new Alias(new Transform(new SetSRID('geom', 4087), 4326), 'geom')
                ]),
                't'
            )
            ->orderBy('continent');
    }

    public function addRelationships(Model $model, object $record): void
    {
        if (! $model instanceof Continent) {
            return;
        }

        $this->addTimezones($model);
    }

    protected function addTimezones(Continent $model): void
    {
        $ids = Timezone::query()
            ->distinct()
            ->select('timezones.id')
            ->withExpression(
                'co',
                Continent::query()
                    ->select(['id', 'name', new Dump('geom')])
                    ->where('id', $model->id)
            )
            ->join('co', new Intersects('timezones.geom', 'co.geom'), '=', new Value(true))
            ->pluck('id');

        $model->timezones()->toggle($ids);
    }
}
