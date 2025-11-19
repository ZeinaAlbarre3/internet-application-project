<?php

namespace App\Domains\Complaint\Data;

use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use Spatie\LaravelData\Data;

class ReplyComplaintData extends Data
{
    public function __construct(
        public string $reply,
    ) {}
}
