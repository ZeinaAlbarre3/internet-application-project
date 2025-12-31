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

class ComplaintCreated implements TraceableEvent, VersionalEvent, InvalidatesComplaintCache,NotifiableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Complaint $complaint,
        public ?int $actorId
    ) {}

    // ===== Tracing =====
    public function action(): string
    {
        return 'complaint.created';
    }

    public function entity(): array
    {
        return ['type' => 'Complaint', 'id' => $this->complaint->id];
    }

    public function meta(): array
    {
        return [
            'status' => $this->complaint->status,
            'title'  => $this->complaint->title,
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
        return 'complaint.created';
    }

    public function before(): ?array
    {
        return null;
    }

    public function after(): ?array
    {
        return [
            'title'       => $this->complaint->title,
            'description' => $this->complaint->description,
            'status'      => $this->complaint->status,
            'user_id'     => $this->complaint->user_id,
        ];
    }

    public function actorId(): ?int
    {
        return $this->actorId;
    }

    // ====Notifications====

    public function notificationKey(): string
    {
        return 'complaint.created';
    }

    public function notificationTitle(): string
    {
        return 'New complaint created';
    }

    public function notificationBody(): string
    {
        return "A new complaint has been submitted: {$this->complaint->reference_number}";
    }

    public function notificationRecipients(): iterable
    {
        return User::role('staff')->get();
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
