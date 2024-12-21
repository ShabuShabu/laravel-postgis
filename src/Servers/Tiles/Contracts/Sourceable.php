<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles\Contracts;

use BackedEnum;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;

interface Sourceable
{
    public function request(Request $request): static;

    public function name(): string | BackedEnum;

    public function layer(): string;

    public function query(): Builder;

    public function columns(): array;
}
