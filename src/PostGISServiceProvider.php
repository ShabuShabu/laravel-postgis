<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS;

use Illuminate\Database\Schema\Blueprint;
use ShabuShabu\PostGIS\Console\ImportGeoData;
use Illuminate\Database\Schema\Grammars\Grammar;
use ShabuShabu\PostGIS\Console\BackupDatabaseTables;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use ShabuShabu\PostGIS\Actions\Contracts;

class PostGISServiceProvider extends PackageServiceProvider
{
    public array $bindings = [
        Contracts\ImportsShapefile::class => Actions\ImportShapefile::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-postgis')
            ->hasConfigFile()
            ->hasCommands(
                ImportGeoData::class,
                BackupDatabaseTables::class,
            )
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('ShabuShabu/laravel-postgis');
            });
    }

    /** @noinspection StaticClosureCanBeUsedInspection */
    public function register(): void
    {
        Grammar::macro('typeBox2d', function () {
            return 'box2d';
        });

        Grammar::macro('typeBox3d', function () {
            return 'box3d';
        });

        Blueprint::macro('box2d', function (string $column) {
            return $this->addColumn('box2d', $column);
        });

        Blueprint::macro('box3d', function (string $column) {
            return $this->addColumn('box3d', $column);
        });
    }
}
