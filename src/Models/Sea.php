<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Models;

use Database\Factories\SeaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sea extends Model
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

    public function oceans(): BelongsToMany
    {
        return $this->belongsToMany(Ocean::class);
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    protected static function newFactory(): SeaFactory
    {
        return new SeaFactory();
    }
}
