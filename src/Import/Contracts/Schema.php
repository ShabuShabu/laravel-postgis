<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Schema
{
    public function builder(): Builder;

    public function shapefileLocation(): string;

    public function sourceId(object $record): string;

    public function mappings(): array;
}
