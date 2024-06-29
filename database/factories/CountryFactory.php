<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use ShabuShabu\PostGIS\Models\Country;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'slug' => fake()->unique()->slug(),
            'iso3166_1a2' => fake()->unique()->countryCode(),
            'iso3166_1a3' => fake()->unique()->countryISOAlpha3(),
            'hash' => md5(fake()->word()),
            'geom' => '0106000020E61000000100000001030000000100000004000000141F44D72A622B40528E1370198E494033A1EEA8D67B2B407FCD8305418D49409374289E0E732B4089FF852F5D8A4940141F44D72A622B40528E1370198E4940',
        ];
    }
}
