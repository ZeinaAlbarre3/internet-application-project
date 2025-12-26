<?php

namespace App\Domains\Complaint\Events;

use App\Domains\Complaint\Interface\InvalidatesComplaintCache;
use App\Domains\Complaint\Models\ComplaintReply;
use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintReplyCreated implements TraceableEvent,InvalidatesComplaintCache
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(Public ComplaintReply $reply)
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
        return 'complaint.reply.created';
    }

    public function entity(): array
    {
        return ['type' => 'ComplaintReply', 'id' => $this->reply->id];
    }

    public function meta(): array
    {
        return [
            'complaint_id' => $this->reply->complaint_id,
            'is_from_staff'=> $this->reply->is_from_staff,
        ];
    }

    public function statusCode(): int
    {
        return 200;
    }
}
