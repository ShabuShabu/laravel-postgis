<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Console;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;
use ShabuShabu\PostGIS\Import\Events\Total;
use ShabuShabu\PostGIS\Import\Events\Saved;
use ShabuShabu\PostGIS\Import\Importers\Seas;
use ShabuShabu\PostGIS\Import\Importers\Oceans;
use Symfony\Component\Console\Helper\ProgressBar;
use ShabuShabu\PostGIS\Import\Importers\Provinces;
use ShabuShabu\PostGIS\Import\Importers\Timezones;
use ShabuShabu\PostGIS\Import\Importers\Countries;
use ShabuShabu\PostGIS\Import\Importers\Continents;

class ImportGeoData extends Command
{
    protected $signature = 'postgis:import {table? : Which table to import}';

    protected $description = 'Import spatial tables';

    protected ?ProgressBar $bar = null;

    public function __invoke(Dispatcher $events): int
    {
        $events->listen(Total::class, $this->initProgressBar(...));
        $events->listen(Saved::class, $this->advanceProgressBar(...));

        $tables = $this->argument('table') ?? $this->choice(
            'Which database table do you want to populate?',
            [
                'All',
                ...config('database.geo_tables')
            ],
            'All'
        );

        if ($tables === 'All') {
            $tables = config('database.geo_tables');
        }

        foreach (Arr::wrap($tables) as $table) {
            $this->importTable($table);
        }

        return static::SUCCESS;
    }

    protected function importTable(string $table): void
    {
        $this->components->info(
            "Transferring data to the <options=bold>$table</> table..."
        );

        $result = match ($table) {
            'continents' => Continents::import(),
            'timezones'  => Timezones::import(),
            'countries'  => Countries::import(),
            'provinces'  => Provinces::import(),
            'oceans'     => Oceans::import(),
            'seas'       => Seas::import(),
        };

        if (! $result) {
            $this->components->error(
                "An unidentified error occurred while importing data for <options=bold>$table</>"
            );

            return;
        }

        [
            'total'   => $total,
            'created' => $created,
            'updated' => $updated,
        ] = $result->rows();

        $this->newLine(2);

        $this->components->info(
            "A total of <options=bold>$created</> rows were created in <options=bold>$table</>"
        );

        $this->components->info(
            "A total of <options=bold>$updated</> rows were updated in <options=bold>$table</>"
        );

        $touched = $created + $updated;

        if ($total !== $touched) {
            $this->components->error(
                "A total of <options=bold>$total</> rows should have been touched in <options=bold>$table</>, but only <options=bold>$touched</> rows actually were!"
            );
        }

        $this->resetProgressBar();
    }

    protected function initProgressBar(Total $event): void
    {
        $this->bar = $this->output->createProgressBar($event->rows);
    }

    protected function advanceProgressBar(): void
    {
        $this->bar?->advance();
    }

    protected function resetProgressBar(): void
    {
        $this->bar?->finish();
        $this->bar = null;
    }
}
