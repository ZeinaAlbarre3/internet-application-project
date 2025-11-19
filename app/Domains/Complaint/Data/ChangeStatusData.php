<?php

namespace App\Domains\Complaint\Data;

use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use Spatie\LaravelData\Data;

class ChangeStatusData extends Data
{
    public function __construct(
        public ?ComplaintStatusEnum $status
    ) {}
}
