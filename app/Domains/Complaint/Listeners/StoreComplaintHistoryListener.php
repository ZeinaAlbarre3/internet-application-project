<?php

namespace App\Domains\Complaint\Listeners;

use App\Domains\Complaint\Interface\VersionalEvent;
use App\Domains\Complaint\Models\ComplaintHistory;

class StoreComplaintHistoryListener
{
    public function handle(object $event): void
    {
        if (! $event instanceof VersionalEvent) return;

        ComplaintHistory::query()->create([
            'complaint_id' => $event->complaintId(),
            'actor_id'     => $event->actorId(),
            'event'        => $event->eventName(),
            'before'       => $event->before(),
            'after'        => $event->after(),
            'occurred_at'  => now(),
        ]);
    }
}
