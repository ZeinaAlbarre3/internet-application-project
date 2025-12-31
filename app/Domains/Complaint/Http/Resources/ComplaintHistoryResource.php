<?php

namespace App\Domains\Complaint\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'event' => $this->event,
            'actor' => [
                'reference_number'    => $this->actor->reference_number,
                'email' => $this->actor?->email,
                'name'  => $this->actor?->name,
            ],
            'before' => $this->before,
            'after'  => $this->after,
            'occurred_at' => $this->occurred_at,
        ];
    }
}
