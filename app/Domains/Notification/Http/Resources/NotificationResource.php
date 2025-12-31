<?php

namespace App\Domains\Notification\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'event_key' => data_get($this->data, 'event_key'),
            'title'     => data_get($this->data, 'title'),
            'body'      => data_get($this->data, 'body'),
            'payload'   => data_get($this->data, 'payload', []),
            'read_at'   => $this->read_at,
            'created_at'=> $this->created_at,
        ];
    }
}
