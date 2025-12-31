<?php

namespace App\Domains\Notification\Interfaces;

interface NotifiableEvent
{
    public function notificationKey(): string;

    public function notificationRecipients(): iterable;

    public function notificationTitle(): string;

    public function notificationBody(): string;

    public function notificationPayload(): array;
}
