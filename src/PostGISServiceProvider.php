<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS;

use Brick\Geo\Engine\PdoEngine;
use Illuminate\Foundation\Application;
use ShabuShabu\PostGIS\Servers\Features;
use ShabuShabu\PostGIS\Servers\Features\Actions\GetFeatureCollection;
use ShabuShabu\PostGIS\Servers\Features\Actions\GetModelGeoJson;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsFeatureCollection;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsModelGeoJson;
use ShabuShabu\PostGIS\Servers\Tiles;
use ShabuShabu\PostGIS\Servers\Tiles\Actions\GetMVTStream;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\GetsMVTStream;
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
        $this->app->bind(GetsModelGeoJson::class, GetModelGeoJson::class);
        $this->app->bind(GetsFeatureCollection::class, GetFeatureCollection::class);

        $this->app->scoped(
            Tiles\Manager::class,
            fn (Application $app) => collect(
                $app->make('config')->get('postgis.tiles.sources', [])
            )->reduce(
                fn (Tiles\Manager $manager, string $source) => $manager->addSource(new $source),
                new Tiles\Manager
            )
        );

        $this->app->scoped(
            Features\Manager::class,
            fn (Application $app) => collect(
                $app->make('config')->get('postgis.features.collections', [])
            )->reduce(
                fn (Features\Manager $manager, string $collection) => $manager->addCollection(new $collection),
                new Features\Manager
            )
        );

        $this->app->scoped(
            PdoEngine::class,
            fn (Application $app) => new PdoEngine(
                $app->make('db.connection')->getPdo()
            )
        );
    }
}
