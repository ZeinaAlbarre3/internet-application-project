<?php

namespace App\Domains\Auth\Events;

use App\Domains\Auth\Models\User;
use App\Domains\Shared\Tracing\Interface\TraceableEvent;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LoginSucceeded implements TraceableEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }

    public function action(): string
    {
        return 'auth.login_succeeded';
    }

    public function entity(): ?array
    {
        return ['type' => 'User', 'id' => $this->user->id];
    }

    public function meta(): array
    {
        return ['email' => $this->user->email];
    }
    public function statusCode(): int
    {
        return 200;
    }
}
