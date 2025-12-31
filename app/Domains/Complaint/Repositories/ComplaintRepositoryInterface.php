<?php

namespace App\Domains\Complaint\Repositories;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Pagination\LengthAwarePaginator;

interface ComplaintRepositoryInterface
{
    public function create(array $attributes);
    public function showAllComplaints($request): LengthAwarePaginator;
    public function showCustomerComplaints(int $userId, $request);
    public function createReply(Complaint $complaint, array $attributes);
    public function markAsRead(Complaint $complaint): Complaint;
    public function assignToStaffAtomic(Complaint $complaint, int $staffId): bool;
    public function assignToStaffWithLock(Complaint $complaint, int $staffId, int $seconds = 10): bool;
    public function paginateHistory(int $complaintId, $request);


}
