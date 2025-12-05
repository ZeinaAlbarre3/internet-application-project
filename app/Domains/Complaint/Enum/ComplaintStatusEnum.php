<?php

namespace App\Domains\Complaint\Enum;

enum ComplaintStatusEnum: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED = 'closed';
}
