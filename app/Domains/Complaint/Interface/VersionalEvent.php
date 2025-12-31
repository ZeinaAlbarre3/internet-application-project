<?php

namespace App\Domains\Complaint\Interface;

interface VersionalEvent
{
    public function complaintId(): int;
    public function eventName(): string;
    public function before(): ?array;
    public function after(): ?array;
    public function actorId(): ?int;
}
