<?php

namespace App\Domains\Complaint\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference_number' => $this->reference_number,
            'title' => $this->title,
            'description' => $this->description,
            'is_read' => $this->is_read,
            'status' => $this->status,
            'assigned_to' => $this->assigned_to,
            'version' => $this->version,
            'replies' => ComplaintReplyResource::collection($this->whenLoaded('replies')),
            'created_at' => $this->created_at,
        ];
    }
}
