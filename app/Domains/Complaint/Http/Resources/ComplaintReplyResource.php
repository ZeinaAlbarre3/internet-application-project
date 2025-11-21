<?php

namespace App\Domains\Complaint\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintReplyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reply' => $this->reply,
            'is_from_staff' => $this->is_from_staff,
            'created_at' => $this->created_at
        ];
    }
}
