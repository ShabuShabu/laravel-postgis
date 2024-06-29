<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Actions\Contracts;

use ShabuShabu\PostGIS\Import\Result;
use ShabuShabu\PostGIS\Import\Contracts\Schema;

interface ImportsShapefile
{
    public function __invoke(Schema $schema): ?Result;
}
