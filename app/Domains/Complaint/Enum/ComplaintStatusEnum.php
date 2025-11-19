<?php

namespace App\Domains\Complaint\Enum;

enum ComplaintStatusEnum: string
{
    case OPEN = 'open';
    case CLOSED = 'closed';
}
