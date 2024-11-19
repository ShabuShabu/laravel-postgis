<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;
use ShabuShabu\PostGIS\PostGISServiceProvider;

class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            PostGISServiceProvider::class,
        ];
    }
}
