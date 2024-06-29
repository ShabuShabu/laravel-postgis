<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Importers;

use App\Models\Timezone;
use Illuminate\Support\Str;
use ShabuShabu\PostGIS\Import\Importer;
use Illuminate\Database\Eloquent\Builder;

class Timezones extends Importer
{
    public function builder(): Builder
    {
        return Timezone::query();
    }

    protected function sourceLocation(): string
    {
        return storage_path('gis/timezones-with-oceans-now.shapefile.zip');
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
