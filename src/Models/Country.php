<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
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

    public function continents(): BelongsToMany
    {
        return $this->belongsToMany(Continent::class);
    }

    public function oceans(): BelongsToMany
    {
        return $this->belongsToMany(Ocean::class);
    }

    public function seas(): BelongsToMany
    {
        return $this->belongsToMany(Sea::class);
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class);
    }
}
