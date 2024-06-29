<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Models;

use Database\Factories\ContinentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Continent extends Model
{
    use HasFactory;

    protected $hidden = [
        'geom',
        'center',
        'bbox_2d',
    ];

    protected function casts(): array
    {
        return [
            'area_km2' => 'integer',
        ];
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    protected static function newFactory(): ContinentFactory
    {
        return new ContinentFactory();
    }
}
