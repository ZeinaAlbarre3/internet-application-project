<?php

namespace App\Domains\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class GenericDatabaseNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $eventKey,
        public string $title,
        public string $body,
        public array $payload = []
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'event_key' => $this->eventKey,
            'title'     => $this->title,
            'body'      => $this->body,
            'payload'   => $this->payload,
        ];
    }
}
