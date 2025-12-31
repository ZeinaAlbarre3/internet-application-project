<?php

namespace App\Domains\Complaint\Events;

use App\Domains\Auth\Models\User;
use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use App\Domains\Complaint\Interface\VersionalEvent;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Notification\Interfaces\NotifiableEvent;
use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintStatusChanged implements TraceableEvent, VersionalEvent, InvalidatesComplaintCache, NotifiableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public string $oldStatus,
        public string $newStatus,
        public ?int $actorId
    ) {}

    // ===== Tracing =====

    public function action(): string
    {
        return 'complaint.status.changed';
    }

    public function entity(): array
    {
        return [
            'type' => 'Complaint',
            'id'   => $this->complaint->id,
        ];
    }

    public function meta(): array
    {
        return [
            'complaint_reference' => $this->complaint->reference_number,
            'old_status'          => $this->oldStatus,
            'new_status'          => $this->newStatus,
        ];
    }

    public function statusCode(): int
    {
        return 200;
    }

    // ===== Versioning =====

    public function complaintId(): int
    {
        return $this->complaint->id;
    }

    public function eventName(): string
    {
        return 'complaint.status.changed';
    }

    public function before(): ?array
    {
        return [
            'status' => $this->oldStatus,
        ];
    }

    public function after(): ?array
    {
        return [
            'status' => $this->newStatus,
        ];
    }

    public function actorId(): ?int
    {
        return $this->actorId;
    }

    // ==== Notification ====

    public function notificationKey(): string
    {
        return 'complaint.status.changed';
    }

    public function notificationTitle(): string
    {
        return 'Complaint status changed';
    }

    public function notificationBody(): string
    {
        return "Your complaint status changed to : {$this->complaint->status}";
    }

    public function notificationRecipients(): iterable
    {
        return User::query()->where('id', $this->complaint->user_id)->get();
    }

    public function notificationPayload(): array
    {
        return [
            'reference_number' => $this->complaint->reference_number,
            'title'            => $this->complaint->title,
            'status'           => $this->complaint->status,
        ];
    }
}
