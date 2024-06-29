<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Actions;

use Closure;
use Throwable;
use ZipArchive;
use RuntimeException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use ShabuShabu\PostGIS\Import\Result;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\RequestException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Client\ConnectionException;
use ShabuShabu\PostGIS\Import\Contracts\Schema;
use ShabuShabu\PostGIS\Import\Contracts\ByoQuery;
use ShabuShabu\PostGIS\Import\Contracts\NeedsRelations;
use ShabuShabu\PostGIS\Actions\Contracts\ImportsShapefile;

class ImportShapefile implements ImportsShapefile
{
    public function __invoke(Schema $schema): ?Result
    {
        $uuid = Str::uuid()->toString();
        $tempTable = '_'.strtolower((string) Str::ulid());

        try {
            $this->unzip(
                $this->download($schema->shapefileLocation(), $uuid),
                $uuid
            );

            $import = Process::timeout(600)->run(
                $this->command($tempTable, $uuid)
            );

            $result = null;
            if ($import->successful()) {
                $result = $this->import($schema, $tempTable);
            }

            $this->cleanup($tempTable, $uuid);

            return $result;
        } catch (Throwable $e) {
            $this->cleanup($tempTable, $uuid);

            throw new RuntimeException($e->getMessage());
        }
    }

    protected function import(Schema $schema, string $tempTable): Result
    {
        $query = $schema instanceof ByoQuery
            ? $schema->tempQuery($tempTable)
            : DB::table($tempTable)->orderBy('gid');

        $result = new Result(
            total: $query->count()
        );

        foreach ($query->lazy() as $record) {
            $columns = collect($schema->mappings())->mapWithKeys(
                fn (Closure|string $source, string $dest) => [
                    $dest => $source instanceof Closure ? $source($record) : $record->$source,
                ]
            );

            $model = $schema->builder()->firstOrCreate([
                'hash' => $schema->sourceId($record),
            ], $columns->toArray());

            if (! $model->wasRecentlyCreated) {
                $model->forceFill($columns->toArray())->save();

                $result->incrementUpdated($model);
            } else {
                $result->incrementCreated($model);
            }

            if ($schema instanceof NeedsRelations) {
                $schema->addRelationships($model, $record);
            }
        }

        return $result;
    }

    /**
     * @see http://www.bostongis.com/pgsql2shp_shp2pgsql_quickguide.bqg
     */
    protected function command(string $tempTable, string $uuid): string
    {
        $shapefile = collect(
            $this->disk()->allFiles($uuid)
        )->first(
            fn (string $file) => Str::endsWith($file, '.shp')
        );

        if (! $shapefile) {
            throw new RuntimeException("No shapefile found in directory [$uuid]");
        }

        return sprintf(
            '%s -s 4326 -c -I %s public.%s | %s -h %s -d %s -U %s',
            config('app.binaries.shp2pgsql'),
            escapeshellarg($this->disk()->path((string) $shapefile)),
            $tempTable,
            config('app.binaries.psql'),
            escapeshellarg($this->config('host')),
            escapeshellarg($this->config('database')),
            escapeshellarg($this->config('username'))
        );
    }

    /**
     * @throws RequestException|ConnectionException
     */
    protected function download(string $url, string $uuid): string
    {
        if (! Str::startsWith($url, 'https://') && File::exists($url)) {
            $contents = File::get($url);
        } else {
            $response = Http::retry(3, 200)
                ->timeout(240)
                ->get($url)
                ->throw();

            $contents = $response->body();
        }

        $this->disk()->put(
            $path = $uuid.'.zip',
            $contents
        );

        return $path;
    }

    protected function unzip(string $path, string $uuid): void
    {
        $zip = new ZipArchive();

        if ($zip->open($this->disk()->path($path)) === true) {
            $zip->extractTo($this->disk()->path($uuid));
            $zip->close();
        } else {
            throw new RuntimeException("Could not unzip <options=bold>$path</>");
        }
    }

    protected function cleanup(string $tempTable, string $uuid): void
    {
        \Illuminate\Support\Facades\Schema::dropIfExists($tempTable);

        $disk = $this->disk();

        if ($disk->exists($file = $uuid.'.zip')) {
            $disk->delete($file);
        }

        if ($disk->exists($uuid)) {
            $disk->deleteDirectory($uuid);
        }
    }

    protected function config(string $name): string
    {
        return config("database.connections.pgsql.$name");
    }

    protected function disk(): Filesystem
    {
        return Storage::disk('shapefiles');
    }
}
