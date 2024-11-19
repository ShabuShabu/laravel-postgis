<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS;

use Illuminate\Foundation\Application;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PostGISServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-postgis')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->askToStarRepoOnGitHub('ShabuShabu/laravel-postgis');
            });
    }

    public function registeringPackage(): void
    {
        $this->app->scoped(
            Geometry::class,
            fn (Application $app) => new Geometry(
                $app->make('db.connection')->getPdo()
            )
        );
    }
}
