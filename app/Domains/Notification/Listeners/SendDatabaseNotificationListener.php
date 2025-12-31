<?php

namespace App\Domains\Notification\Listeners;

use App\Domains\Notification\Interfaces\NotifiableEvent;
use App\Domains\Notification\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Facades\Notification;

class SendDatabaseNotificationListener
{
    public function handle(object $event): void
    {
        if (! $event instanceof NotifiableEvent) {
            return;
        }

        Notification::send(
            $event->notificationRecipients(),
            new GenericDatabaseNotification(
                $event->notificationKey(),
                $event->notificationTitle(),
                $event->notificationBody(),
                $event->notificationPayload()
            )
        );
    }
}
