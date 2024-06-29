<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import;

class Result
{
    protected int $created = 0;

    protected int $updated = 0;

    public function __construct(
        protected int $total
    ) {
        Events\Total::dispatch($this->total);
    }

    public function incrementCreated(mixed $model): void
    {
        $this->created++;

        Events\Saved::dispatch($model, $this->rows());
        Events\Created::dispatch($model, $this->rows());
    }

    public function incrementUpdated(mixed $model): void
    {
        $this->updated++;

        Events\Saved::dispatch($model, $this->rows());
        Events\Updated::dispatch($model, $this->rows());
    }

    public function rows(): array
    {
        return [
            'total' => $this->total,
            'created' => $this->created,
            'updated' => $this->updated,
        ];
    }
}
