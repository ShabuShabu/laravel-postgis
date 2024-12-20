<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\Position;

use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_MakeEnvelope.html
 */
readonly class MakeEnvelope implements GisExpression
{
    use Stringable;

    public function __construct(
        private float | int $xmin,
        private float | int $ymin,
        private float | int $xmax,
        private float | int $ymax,
        private int $srid,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $xmin = $this->stringize($grammar, $this->asNumber($this->xmin));
        $ymin = $this->stringize($grammar, $this->asNumber($this->ymin));
        $xmax = $this->stringize($grammar, $this->asNumber($this->xmax));
        $ymax = $this->stringize($grammar, $this->asNumber($this->ymax));
        $srid = $this->stringize($grammar, $this->asNumber($this->srid));

        return "ST_MakeEnvelope($xmin, $ymin, $xmax, $ymax, $srid)";
    }
}
