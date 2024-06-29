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
        Schema::create('provinces', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->text('name');
            $table->text('slug');
            $table->text('hash');
            $table->integer('area_km2')->storedAs(area());
            $table->timestampsTz(null);
            $table->geometry('center', 'point', 4326)->storedAs(new Centroid('geom'));
            $table->box2d('bbox_2d')->storedAs(new BoxTwoD('geom'));
            $table->geometry('geom', 'multipolygon', 4326);

            $table->uniqueIndex('name');
            $table->uniqueIndex('hash');
            $table->uniqueIndex('slug');
            $table->uniqueIndex('iso3166_1a2');
            $table->uniqueIndex('iso3166_1a3');
            $table->spatialIndex('geom');
        });

        Schema::create('country_province', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['country_id', 'province_id']);
        });

        Schema::create('ocean_province', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('ocean_id')
                ->constrained('oceans')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['province_id', 'ocean_id']);
        });

        Schema::create('province_sea', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('sea_id')
                ->constrained('seas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['province_id', 'sea_id']);
        });

        Schema::create('province_timezone', static function (Blueprint $table) {
            $table->identity(always: true)->primary();
            $table->foreignId('timezone_id')
                ->constrained('timezones')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('province_id')
                ->constrained('provinces')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->uniqueIndex(['timezone_id', 'province_id']);
        });
    }
};
