<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS;

use Illuminate\Foundation\Application;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsGeoJson;
use ShabuShabu\PostGIS\Servers\Features\GetGeoJson;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\GetsMVTStream;
use ShabuShabu\PostGIS\Servers\Tiles\GetMVTStream;
use ShabuShabu\PostGIS\Servers\Tiles\SourceManager;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PostGISServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-postgis')
            ->hasConfigFile()
            ->hasRoute('servers')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('ShabuShabu/laravel-postgis');
            });
    }

    public function registeringPackage(): void
    {
        $this->app->bind(GetsMVTStream::class, GetMVTStream::class);
        $this->app->bind(GetsGeoJson::class, GetGeoJson::class);

        $this->app->scoped(
            SourceManager::class,
            fn (Application $app) => collect(
                $app->make('config')->get('postgis.tiles.sources', [])
            )->reduce(
                fn (SourceManager $manager, string $source) => $manager->addSource(new $source),
                new SourceManager
            )
        );

        $this->app->scoped(
            Geometry::class,
            fn (Application $app) => new Geometry(
                $app->make('db.connection')->getPdo()
            )
        );
    }
}
