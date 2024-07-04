<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Concerns;

use Illuminate\Support\Str;

trait NeedsUniqueSlug
{
    protected array $slugs = [];

    protected function uniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;

        while (in_array($slug, $this->slugs, true)) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        $this->slugs[] = $slug;
        $this->slugs = array_unique($this->slugs);

        return $slug;
    }
}
