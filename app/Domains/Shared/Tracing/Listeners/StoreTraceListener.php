<?php

namespace App\Domains\Shared\Tracing\Listeners;

use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use App\Domains\Shared\Tracing\Models\Trace;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class StoreTraceListener
{
    public function handle(object $event): void
    {
        if (! $event instanceof TraceableEvent) {
            return;
        }

        $entity = $event->entity();


        Trace::query()->create([
            'action'           => $event->action(),
            'span_id'          => Str::ulid(),
            'entity_type'      => $entity['type'] ?? null,
            'entity_id'        => $entity['id'] ?? null,
            'user_id'          => Auth::id(),
            'route'            => Request::path(),
            'method'           => Request::method(),
            'ip'               => Request::ip(),
            'status_code'      => $event->statusCode(),
            'meta'             => $event->meta(),
            'occurred_at'      => now(),
        ]);
    }
}
