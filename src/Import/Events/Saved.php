<?php

declare(strict_types=1);

namespace ShabuShabu\PostGIS\Import\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class Saved
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public function __construct(
        public mixed $model,
        public array $rows,
    ) {
    }
}
