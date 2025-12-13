<?php

namespace App\Domains\Complaint\Services;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Data\CreateComplaintData;
use App\Domains\Complaint\Data\ReplyComplaintData;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Exceptions\Types\CustomException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ComplaintService
{
    public function __construct(
        protected ComplaintRepositoryInterface $complaintRepository,
    )
    {
    }

    public function createComplaint(CreateComplaintData $data)
    {
        Cache::tags(['complaints'])->flush();

        return $this->complaintRepository->create([
            ...$data->toArray(),
            'user_id' => Auth::id(),
        ]);
    }

    public function replyToComplaint(Complaint $complaint, ReplyComplaintData $data): Complaint
    {
        $user = Auth::user();

        if($complaint->status === ComplaintStatusEnum::CLOSED->value) throw new CustomException('The Complaint Closed you can not reply',422);

        $this->complaintRepository->createReply($complaint, $data->toCreateArray($user));

        Cache::tags(['complaints'])->flush();

        return $complaint->refresh()->load('replies');
    }

    public function showComplaint(Complaint $complaint): Complaint
    {
       $complaint = $this->complaintRepository->markAsRead($complaint);

        return $complaint->load('replies');
    }

    public function getCustomerComplaints($request): LengthAwarePaginator
    {
        return $this->complaintRepository->showCustomerComplaints(
            Auth::id(),
            $request
        );
    }

    public function assignToMe(Complaint $complaint): Complaint
    {
        Cache::tags(['complaints'])->flush();

        $user = Auth::user();
        $assigned = $this->complaintRepository->assignToStaffAtomic($complaint, $user->id);

        if ($assigned) {
            return $complaint->refresh();
        }

        $assignedWithLock = $this->complaintRepository->assignToStaffWithLock($complaint, $user->id, 10);

        if ($assignedWithLock) {
            return $complaint->refresh();
        }

        throw new CustomException('Conflict: complaint already assigned', 409);
    }

    public function changeStatusOptimistic(Complaint $complaint, ChangeStatusData $data): Complaint
    {
        $complaint = $this->complaintRepository->updateStatusOptimistic($complaint, $data);

        Cache::tags(['complaints'])->flush();

        return $complaint->refresh()->load('replies');
    }

}
