<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Contracts;

use Illuminate\Database\Eloquent\Model;

interface NeedsRelations
{
    public function addRelationships(Model $model, object $record): void;
}
