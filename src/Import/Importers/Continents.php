<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Expressions\SetSRID;
use ShabuShabu\PostGIS\Expressions\Transform;
use ShabuShabu\PostGIS\Import\Contracts\ByoQuery;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Import\Importer;
use ShabuShabu\PostGIS\Models\Continent;
use Tpetry\QueryExpressions\Language\Alias;

use function ShabuShabu\PostGIS\query;

class Continents extends Importer implements ByoQuery, NeedsRelations
{
    public function builder(): Builder
    {
        return query(config('postgis.models.continent'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.continents');
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
                'Africa' => 'AF',
                'Antarctica' => 'AN',
                'Asia' => 'AS',
                'Australia' => 'AU',
                'Europe' => 'EU',
                'North America' => 'NA',
                'Oceania' => 'OC',
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
                    new Alias(new Transform(new SetSRID('geom', 4087), 4326), 'geom'),
                ]),
                't'
            )
            ->orderBy('continent');
    }

    public function addRelationships(Model $model, object $record): void
    {
        if ($model instanceof Continent) {
            $this->addTimezones($model);
        }
    }

    protected function addTimezones(Continent $model): void
    {
        $ids = $this->ids(
            $model->getKey(),
            config('postgis.models.timezone'),
            config('postgis.models.continent'),
        );

        $model->timezones()->toggle($ids);
    }
}
