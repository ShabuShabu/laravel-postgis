<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Features\Controllers;

use Closure;
use Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use ShabuShabu\PostGIS\Servers\Features\Contracts\GetsFeatureCollection;
use ShabuShabu\PostGIS\Servers\Features\Manager;
use ShabuShabu\PostGIS\Servers\Mime;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;

class Collection
{
    public function __invoke(Request $request, Manager $manager, GetsFeatureCollection $getFeatureCollection, string $collectionName): StreamedJsonResponse
    {
        abort_unless($manager->has($collectionName), StreamedJsonResponse::HTTP_NOT_FOUND);

        $collection = $manager->get($collectionName)->request($request);

        Gate::authorize('access-collection-server', [$collectionName, $collection]);

        $get = static fn () => $getFeatureCollection($collection);

        return response()->streamJson([
            'type' => 'FeatureCollection',
            'features' => $this->yieldFeatures($get),
        ], StreamedJsonResponse::HTTP_OK, [
            'Content-Type' => Mime::GEOJSON->value,
        ]);
    }

    protected function yieldFeatures(Closure $get): Generator
    {
        foreach ($get() as $feature) {
            yield $feature->geojson;
        }
    }
}
