<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseTables extends Command
{
    protected $signature = 'postgis:backup {action? : The context, either dump or restore}';

    protected $description = 'Dumps or restores any special table';

    protected int $timeout = 1800;

    public function __invoke(): int
    {
        $context = $this->argument('action') ?? $this->components->choice(
            'What do you want to do?',
            ['dump', 'restore'],
            'dump'
        );

        $tables = array_merge(array_keys(config('postgis.importers')), [
            'continent_timezone',
            'continent_country',
            'ocean_timezone',
            'ocean_sea',
            'sea_timezone',
            'country_ocean',
            'country_province',
            'country_sea',
            'country_timezone',
            'ocean_province',
            'province_sea',
            'province_timezone',
            'continent_disputed_area',
            'country_disputed_area',
            'disputed_area_province',
            'disputed_area_ocean',
            'disputed_area_sea',
            'disputed_area_timezone',
        ]);

        return match ($context) {
            'dump' => $this->dump($tables),
            'restore' => $this->restore($tables),
        };
    }

    protected function dump(array | string $tables): int
    {
        foreach (Arr::wrap($tables) as $table) {
            $this->dumpTable($table);
        }

        return static::SUCCESS;
    }

    protected function dumpTable(string $table): void
    {
        $file = "$table.dump";

        if ($this->disk()->exists($file)) {
            $this->disk()->delete($file);
        }

        $config = config('database.connections.pgsql');

        $command = sprintf(
            'pg_dump -F c --data-only --no-owner --table=%s --dbname=postgresql://%s:password@%s:%d/%s?password=%s > %s',
            $table,
            $config['username'],
            $config['host'],
            $config['port'],
            $config['database'],
            urlencode($config['password']),
            $destination = $this->disk()->path($file),
        );

        $result = Process::timeout($this->timeout)->run($command);

        if ($result->successful()) {
            $this->components->info("Table <options=bold>$table</> was successfully backed up to:");
            $this->components->info("<options=bold>$destination</>");
        } else {
            $this->components->error("Could not backup table <options=bold>$table</>");
        }
    }

    protected function restore(array | string $tables): int
    {
        foreach (Arr::wrap($tables) as $table) {
            $this->restoreTable($table);
        }

        return static::SUCCESS;
    }

    protected function restoreTable(string $table): void
    {
        $file = "$table.dump";

        if (! $this->disk()->exists($file)) {
            $this->components->error("Backup for table <options=bold>$table</> does not exist");

            return;
        }

        $config = config('database.connections.pgsql');

        $command = sprintf(
            'pg_restore -1 --dbname=postgresql://%s:password@%s:%d/%s?password=%s %s',
            $config['username'],
            $config['host'],
            $config['port'],
            $config['database'],
            urlencode($config['password']),
            $source = $this->disk()->path($file),
        );

        $result = Process::timeout($this->timeout)->run($command);

        if ($result->successful()) {
            $this->components->info("Table <options=bold>$table</> was successfully restored from:");
            $this->components->info("<options=bold>$source</>");
        } else {
            $this->components->error("Could not restore table <options=bold>$table</>");
        }
    }

    protected function disk(): Filesystem
    {
        return Storage::disk('sql');
    }
}
