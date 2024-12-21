<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Servers\Tiles;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use ShabuShabu\PostGIS\Servers\Mime;
use ShabuShabu\PostGIS\Servers\Tiles\Contracts\GetsMVTStream;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Controller
{
    public function __invoke(Request $request, SourceManager $manager, GetsMVTStream $getMvtStream, string $sourceNames, int $z, int $x, int $y): StreamedResponse
    {
        $names = array_map('trim', explode(',', $sourceNames));

        Gate::authorize('access-tile-server', [$names]);

        $sources = collect($names)->filter(
            static fn (string $name) => $manager->isSource($name)
        )->values()->map(
            static fn (string $name) => $manager->source($name)->request($request)
        );

        abort_if($sources->isEmpty(), Response::HTTP_NO_CONTENT);

        $stream = $getMvtStream($sources, $z, $x, $y);

        // We return a 204 here to avoid being overrun by console errors
        abort_unless(is_resource($stream), Response::HTTP_NO_CONTENT);

        defer(static fn () => fclose($stream));

        $stats = fstat($stream);

        return response()->stream(function () use ($stream) {
            while (! feof($stream) && connection_status() === 0) {
                echo fread($stream, 8192);
                ob_flush();
                flush();
            }
        }, Response::HTTP_OK, [
            'Cache-Control' => config('postgis.tiles.cache_control'),
            'Content-Length' => $stats['size'],
            'Content-Type' => Mime::MVT->value,
        ]);
    }
}
