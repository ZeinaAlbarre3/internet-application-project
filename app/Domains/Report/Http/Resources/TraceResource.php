<?php

namespace App\Domains\Report\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TraceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'reference_number' => $this->reference_number,
            'action'           => $this->action,
            'status_code'      => $this->status_code,
            'user' => [
                'reference_number' => $this->user?->reference_number,
                'email' => $this->user?->email,
            ],
            'route'            => $this->route,
            'method'           => $this->method,
            'ip'               => $this->ip,
            'meta'             => $this->meta,
            'occurred_at'      => $this->occurred_at,
        ];
    }
}
