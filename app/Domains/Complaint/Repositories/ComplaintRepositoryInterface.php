<?php

namespace App\Domains\Complaint\Repositories;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Data\CreateComplaintData;
use App\Domains\Complaint\Data\ReplyComplaintData;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Pagination\LengthAwarePaginator;

interface ComplaintRepositoryInterface
{
    public function create(array $attributes);
    public function showAllComplaints($request): LengthAwarePaginator;
    public function showCustomerComplaints(int $userId, $request);
    public function createReply(Complaint $complaint, array $attributes);
    public function updateStatus(Complaint $complaint, ChangeStatusData $data): Complaint;


}
