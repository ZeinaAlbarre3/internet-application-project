<?php

namespace App\Domains\Complaint\Events;

use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use App\Domains\Complaint\Interface\VersionalEvent;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintAssigned implements InvalidatesComplaintCache, VersionalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Complaint $complaint,
        public string $toStaffReference,
        public ?int $actorId
    ) {}

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

    public function complaintId(): int { return $this->complaint->id; }
    public function eventName(): string { return 'complaint.assigned'; }

    public function before(): ?array { return null; }

    public function after(): ?array  { return ['assigned_to' => $this->toStaffReference]; }
    public function actorId(): ?int { return $this->actorId; }
}
