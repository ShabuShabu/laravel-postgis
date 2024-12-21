<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Contracts;

use BackedEnum;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

interface Collectable
{
    public function name(): string | BackedEnum;

    public function request(Request $request): static;

    public function query(): Builder;
}
