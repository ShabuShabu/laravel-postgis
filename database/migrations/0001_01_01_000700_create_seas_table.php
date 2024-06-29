<?php

declare(strict_types=1);

use App\Support\Expressions\GIS\BoxTwoD;
use App\Support\Expressions\GIS\Centroid;
use Illuminate\Database\Migrations\Migration;
use Tpetry\PostgresqlEnhanced\Schema\Blueprint;
use Tpetry\PostgresqlEnhanced\Support\Facades\Schema;

use function Database\area;

return new class () extends Migration
{
    public function up(): void
    {
        Schema::create('seas', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->text('name');
            $table->text('slug');
            $table->text('hash');
            $table->integer('area_km2')->storedAs(area());
            $table->timestampsTz(null);
            $table->geometry('center', 'point', 4326)->storedAs(new Centroid('geom'));
            $table->box2d('bbox_2d')->storedAs(new BoxTwoD('geom'));
            $table->geometry('geom', 'multipolygon', 4326);

            $table->uniqueIndex('name');
            $table->uniqueIndex('slug');
            $table->uniqueIndex('hash');
            $table->spatialIndex('geom');
        });

        Schema::create('ocean_sea', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('ocean_id')
                ->constrained('oceans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('sea_id')
                ->constrained('seas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['ocean_id', 'sea_id']);
        });

        Schema::create('sea_timezone', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('timezone_id')
                ->constrained('timezones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('sea_id')
                ->constrained('seas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['timezone_id', 'sea_id']);
        });
    }
};
