<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Importer;

use function ShabuShabu\PostGIS\query;

class Timezones extends Importer
{
    public function builder(): Builder
    {
        return query(config('postgis.models.timezone'));
    }

    protected function sourceLocation(): string
    {
        return config('postgis.sources.timezones');
    }

    public function sourceId(object $record): string
    {
        return md5($record->tzid);
    }

    public function mappings(): array
    {
        return [
            'geom' => 'geom',
            'name' => 'tzid',
            'slug' => fn (object $record) => Str::slug(str_replace('/', '-', $record->tzid)),
        ];
    }
}
