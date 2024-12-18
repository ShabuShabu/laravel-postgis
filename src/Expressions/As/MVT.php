<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Expressions\As;

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Grammar;
use ShabuShabu\PostGIS\Expressions\Concerns\Stringable;
use ShabuShabu\PostGIS\Expressions\Contracts\GisExpression;

/**
 * @see https://postgis.net/docs/ST_AsMVT.html
 */
readonly class MVT implements GisExpression
{
    use Stringable;

    public function __construct(
        private string | Expression $row,
        private string $name = 'default',
        private ?int $extent = null,
        private ?string $geomName = null,
        private ?string $featureIdName = null,
    ) {}

    public function getValue(Grammar $grammar): string
    {
        $params = $this->toParams([
            'row' => $this->stringize($grammar, $this->row),
            'name' => $this->cast($grammar, $this->name),
            'extent' => $this->cast($grammar, $this->extent),
            'geom_name' => $this->cast($grammar, $this->geomName),
            'feature_id_name' => $this->cast($grammar, $this->featureIdName),
        ]);

        return "ST_AsMVT($params)";
    }
}
