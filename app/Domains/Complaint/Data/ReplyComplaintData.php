<?php

namespace App\Domains\Complaint\Data;

use App\Domains\Auth\Models\User;
use Spatie\LaravelData\Data;

class ReplyComplaintData extends Data
{
    public function __construct(
        public string $reply,
    ) {}

    public function toCreateArray(User $user): array
    {
        return [
            'user_id'       => $user->id,
            'reply'         => $this->reply,
            'is_from_staff' => $user->hasRole('staff'),
        ];
    }
}
