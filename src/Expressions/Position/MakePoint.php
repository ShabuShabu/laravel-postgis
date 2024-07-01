<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Position;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_MakePoint.html
 */
readonly class MakePoint implements GisExpression
{
    use Stringable;

    public function __construct(
        private float | string | Expression $lng,
        private float | string | Expression $lat,
        private null | string | float | Expression $elevation = null,
        private null | string | float | Expression $timestamp = null
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $lng = $this->stringize($grammar, $this->asNumber($this->lng));
        $lat = $this->stringize($grammar, $this->asNumber($this->lat));

        if ($this->elevation && $this->timestamp) {
            $elevation = $this->stringize($grammar, $this->asNumber($this->elevation));
            $timestamp = $this->stringize($grammar, $this->asNumber($this->timestamp));

            return "ST_MakePoint($lng, $lat, $elevation, $timestamp)";
        }

        if ($this->elevation) {
            $elevation = $this->stringize($grammar, $this->asNumber($this->elevation));

            return "ST_MakePoint($lng, $lat, $elevation)";
        }

        return "ST_MakePoint($lng, $lat)";
    }
}
