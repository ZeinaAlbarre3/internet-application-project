<?php

namespace App\Domains\Complaint\Events;

use App\Domains\Auth\Models\User;
use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use App\Domains\Complaint\Interface\VersionalEvent;
use App\Domains\Complaint\Models\ComplaintReply;
use App\Domains\Notification\Interfaces\NotifiableEvent;
use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintReplyCreated implements TraceableEvent, VersionalEvent, InvalidatesComplaintCache, NotifiableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ComplaintReply $reply,
        public ?int $actorId
    ) {}

    // ===== Tracing =====

    public function action(): string
    {
        return 'complaint.reply.created';
    }

    public function entity(): array
    {
        return [
            'type' => 'ComplaintReply',
            'id'   => $this->reply->id,
        ];
    }

    public function meta(): array
    {
        return [
            'complaint_reference' => $this->reply->complaint->reference_number,
            'reply_reference'     => $this->reply->reference_number,
            'is_from_staff'       => $this->reply->is_from_staff,
        ];
    }

    public function statusCode(): int
    {
        return 201; // created
    }

    // ===== Versioning =====

    public function complaintId(): int
    {
        return $this->reply->complaint->id;
    }

    public function eventName(): string
    {
        return 'complaint.reply.added';
    }

    public function before(): ?array
    {
        return null;
    }

    public function after(): ?array
    {
        return [
            'reply_reference'      => $this->reply->reference_number,
            'reply'       => $this->reply->reply,
            'is_from_staff' => $this->reply->is_from_staff,
        ];
    }

    public function actorId(): ?int
    {
        return $this->actorId;
    }

    // ==== Notification ====

    public function notificationKey(): string
    {
        return 'complaint.reply.created';
    }

    public function notificationTitle(): string
    {
        return 'New complaint replied';
    }

    public function notificationBody(): string
    {
        return "A new complaint has been replied: {$this->reply->complaint->reference_number}";
    }

    public function notificationRecipients(): iterable
    {
        $complaint = $this->reply->complaint;

        if ($this->reply->is_from_staff) {
            return User::query()
                ->where('id', $complaint->user_id)
                ->get();
        }

        if (!is_null($complaint->assigned_to)) {
            return User::query()
                ->where('id', $complaint->assigned_to)
                ->get();
        }

        return User::role('staff')->get();

        // return collect();
    }

    public function notificationPayload(): array
    {
        return [
            'reply_reference'         => $this->reply->reference_number,
            'reference_number' => $this->reply->complaint->reference_number,
            'is_from_staff'    => (bool) $this->reply->is_from_staff,
        ];
    }
}
