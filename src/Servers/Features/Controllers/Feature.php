<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use ShabuShabu\PostGIS\Servers\Features\Contracts\Geomable;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsModelGeoJson;
use ShabuShabu\PostGIS\Servers\Mime;
use ShabuShabu\Uid\Service\Uid;
use Throwable;

class Feature
{
    public function __invoke(GetsModelGeoJson $getGeoJson, string $uid): JsonResponse
    {
        try {
            $model = Uid::make()->decodeToModel($uid);
        } catch (Throwable) {
            $model = false;
        }

        abort_unless($model instanceof Geomable, JsonResponse::HTTP_NOT_FOUND);

        Gate::authorize('access-feature-server', [$model]);

        $ttl = config('postgis.features.cache_ttl');

        $get = static fn () => $getGeoJson($model);

        $geoJson = is_null($ttl) ? $get() : Cache::remember(
            sprintf('server:features:%s', $uid),
            now()->addMinutes($ttl),
            $get
        );

        return JsonResponse::fromJsonString($geoJson)->header(
            'Content-Type',
            Mime::GEOJSON->value,
        );
    }
}
