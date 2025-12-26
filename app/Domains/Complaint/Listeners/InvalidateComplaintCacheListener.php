<?php

namespace App\Domains\Complaint\Listeners;

use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use Illuminate\Support\Facades\Cache;

class InvalidateComplaintCacheListener
{
    public function handle(object $event): void
    {
        if (! $event instanceof InvalidatesComplaintCache) {
            return;
        }

        Cache::tags(['complaints'])->flush();
    }
}
