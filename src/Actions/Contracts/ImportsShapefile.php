<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Actions\Contracts;

use ShabuShabu\PostGIS\Import\Contracts\Schema;
use ShabuShabu\PostGIS\Import\Result;

interface ImportsShapefile
{
    public function __invoke(Schema $schema): ?Result;
}
