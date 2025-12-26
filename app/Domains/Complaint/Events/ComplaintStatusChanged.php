<?php

namespace App\Domains\Complaint\Events;

use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusChanged implements TraceableEvent,InvalidatesComplaintCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(Public Complaint $complaint)
    {

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function action(): string
    {
        return 'complaint.status.changed';
    }

    public function entity(): array
    {
        return ['type' => 'Complaint', 'id' => $this->complaint->id];
    }

    public function meta(): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'status'=> $this->complaint->status,
        ];
    }

    public function statusCode(): int
    {
        return 200;
    }
}
