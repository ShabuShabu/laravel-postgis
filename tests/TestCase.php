<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use ShabuShabu\PostGIS\PostGISServiceProvider;
use Tpetry\PostgresqlEnhanced\PostgresqlEnhancedServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            PostgresqlEnhancedServiceProvider::class,
            PostGISServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/app/database/migrations');
    }
}
