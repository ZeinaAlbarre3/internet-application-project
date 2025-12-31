<?php

namespace App\Domains\Complaint\Services;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Data\CreateComplaintData;
use App\Domains\Complaint\Data\ReplyComplaintData;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Events\ComplaintAssigned;
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

            DB::afterCommit(fn () => event(new ComplaintCreated($complaint, Auth::id())));

            return $complaint;
        });
    }

    public function replyToComplaint(Complaint $complaint, ReplyComplaintData $data): Complaint
    {
        return DB::transaction(function () use ($complaint,$data) {

            $user = Auth::user();

            if ($complaint->status === ComplaintStatusEnum::CLOSED->value) throw new CustomException('The Complaint Closed you can not reply', 422);

            $complaintReply = $this->complaintRepository->createReply($complaint, $data->toCreateArray($user));

            DB::afterCommit(fn () => event(new ComplaintReplyCreated($complaintReply, Auth::id())));

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

            $user = Auth::user();
            $assigned = $this->complaintRepository->assignToStaffAtomic($complaint, $user->id);

            if ($assigned) {
                DB::afterCommit(fn () => event(new ComplaintAssigned($complaint, $user->reference_number, Auth::id())));

                return $complaint->refresh();
            }

            $assignedWithLock = $this->complaintRepository->assignToStaffWithLock($complaint, $user->id, 10);

            if ($assignedWithLock) {
                DB::afterCommit(fn () => event(new ComplaintAssigned($complaint, $user->reference_number, Auth::id())));

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

            DB::afterCommit(fn () => event(new ComplaintStatusChanged($complaint, $oldStatus, $complaint->status,  Auth::id())));

            return $complaint->refresh()->load('replies');
        });
    }

    public function getComplaintHistory($request,Complaint $complaint)
    {
        return $this->complaintRepository->paginateHistory($complaint->id,$request);
    }

}
