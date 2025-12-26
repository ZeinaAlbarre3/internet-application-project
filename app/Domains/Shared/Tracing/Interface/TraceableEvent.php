<?php

namespace App\Domains\Shared\Tracing\Interface;

interface TraceableEvent
{
    public function action(): string;
    public function entity(): ?array;
    public function meta(): array;
    public function statusCode(): int;
}
