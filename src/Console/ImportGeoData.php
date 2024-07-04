<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Console;

use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use ShabuShabu\PostGIS\Import\Events\Saved;
use ShabuShabu\PostGIS\Import\Events\Total;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportGeoData extends Command
{
    protected $signature = 'postgis:import {table? : Which table to import}
                                           {--source= : The path/url for the data, leave empty to fetch from source}';

    protected $description = 'Import spatial tables';

    protected ?ProgressBar $bar = null;

    public function __invoke(Dispatcher $events): int
    {
        $events->listen(Total::class, $this->initProgressBar(...));
        $events->listen(Saved::class, $this->advanceProgressBar(...));

        $tableNames = array_keys(config('postgis.importers'));

        $tables = $this->argument('table') ?? $this->choice(
            'Which database table do you want to populate?',
            [
                'All',
                ...$tableNames,
            ],
            'All'
        );

        if ($tables === 'All') {
            $tables = $tableNames;
            $source = null;
        } else {
            $source = $this->option('source') ?? $this->components->ask('Do you want to use a different source for the data? If so, enter a path or URL!');

            if ($source && File::exists(storage_path($source))) {
                $source = storage_path($source);
            }
        }

        foreach (Arr::wrap($tables) as $table) {
            $this->importTable($table, $source);
        }

        return static::SUCCESS;
    }

    protected function importTable(string $table, ?string $source): void
    {
        $this->components->info(
            "Transferring data to the <options=bold>$table</> table..."
        );

        $importers = config('postgis.importers');

        $class = $importers[$table] ?? null;

        if (! $class || ! class_exists($class) || ! method_exists($class, 'import')) {
            $this->components->error(
                "No importer exists for table <options=bold>$table</>"
            );

            return;
        }

        if (! $result = $class::import($source)) {
            $this->components->error(
                "An unidentified error occurred while importing data for <options=bold>$table</>"
            );

            return;
        }

        [
            'total' => $total,
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
