<?php

namespace App\Domains\Complaint\Data;

use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use phpDocumentor\Reflection\Types\Boolean;
use Spatie\LaravelData\Data;

class CreateComplaintData extends Data
{
    public function __construct(
        public string $title,
        public string $description,
        public bool $is_read = false,
        public ComplaintStatusEnum $status = ComplaintStatusEnum::OPEN,

    ) {}
}
