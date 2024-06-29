<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Saved
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public mixed $model,
        public array $rows,
    ) {}
}
