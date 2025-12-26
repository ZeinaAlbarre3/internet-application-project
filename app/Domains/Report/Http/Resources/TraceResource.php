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
                'id'    => $this->user_id,
                'email' => $this->user?->email,
            ],
            'route'            => $this->route,
            'method'           => $this->method,
            'ip'               => $this->ip,
            'entity' => [
                'type' => $this->entity_type,
                'id'   => $this->entity_id,
            ],
            'meta'             => $this->meta,
            'occurred_at'      => $this->occurred_at,
        ];
    }
}
