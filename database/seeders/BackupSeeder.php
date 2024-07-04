<?php

declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use ShabuShabu\PostGIS\Commands\BackupDatabaseTables;

class BackupSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call(BackupDatabaseTables::class, ['action' => 'restore']);
    }
}
