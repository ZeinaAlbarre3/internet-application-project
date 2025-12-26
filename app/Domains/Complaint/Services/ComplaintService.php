<?php

namespace App\Domains\Complaint\Services;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Data\CreateComplaintData;
use App\Domains\Complaint\Data\ReplyComplaintData;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Events\ComplaintCreated;
use App\Domains\Complaint\Events\ComplaintReplyCreated;
use App\Domains\Complaint\Events\ComplaintStatusChanged;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Exceptions\Types\CustomException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ComplaintService
{
    public function __construct(
        protected ComplaintRepositoryInterface $complaintRepository,
    )
    {
    }

    public function createComplaint(CreateComplaintData $data)
    {
        return DB::transaction(function () use ($data) {

            $complaint = $this->complaintRepository->create([
                ...$data->toArray(),
                'user_id' => Auth::id(),
            ]);

            event(new ComplaintCreated($complaint));

            return $complaint;
        });
    }

    public function replyToComplaint(Complaint $complaint, ReplyComplaintData $data): Complaint
    {
        return DB::transaction(function () use ($complaint,$data) {

            $user = Auth::user();

            if ($complaint->status === ComplaintStatusEnum::CLOSED->value) throw new CustomException('The Complaint Closed you can not reply', 422);

            $complaintReply = $this->complaintRepository->createReply($complaint, $data->toCreateArray($user));

            event(new ComplaintReplyCreated($complaintReply));

            return $complaint->refresh()->load('replies');
        });
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
        return DB::transaction(function () use ($complaint) {

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
        });
    }

    public function changeStatusOptimistic(Complaint $complaint, ChangeStatusData $data): Complaint
    {
        return DB::transaction(function () use ($complaint,$data) {

            $oldStatus = $complaint->status;

            $complaint = $this->complaintRepository->updateStatusOptimistic($complaint, $data);

            event(new ComplaintStatusChanged($complaint,$oldStatus,$complaint->status));

            return $complaint->refresh()->load('replies');
        });
    }

}
