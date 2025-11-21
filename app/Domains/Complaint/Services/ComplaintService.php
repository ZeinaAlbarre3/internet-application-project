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

class ComplaintService
{
    public function __construct(
        protected ComplaintRepositoryInterface $complaintRepository,
    )
    {
    }

    public function createComplaint(CreateComplaintData $data)
    {
        return $this->complaintRepository->create([
            ...$data->toArray(),
            'user_id' => Auth::id(),
        ]);
    }

    public function replyToComplaint(Complaint $complaint, ReplyComplaintData $data): Complaint
    {
        $user = Auth::user();

        if($complaint->status === ComplaintStatusEnum::CLOSED->value) throw new CustomException('The Complaint Closed you can not reply',422);

        $this->complaintRepository->createReply($complaint, [
            'user_id'      => $user->id,
            'reply'      => $data->reply,
            'is_from_staff'=> $user->hasRole('staff'),
        ]);

        return $complaint->refresh()->load('replies');
    }

    public function changeStatus(Complaint $complaint, ChangeStatusData $data): Complaint
    {
        $complaint = $this->complaintRepository->updateStatus($complaint, $data);

        return $complaint->refresh()->load('replies');
    }

    public function showComplaint(Complaint $complaint): Complaint
    {
        if (!$complaint->is_read) {
            $complaint->update(['is_read' => true]);
        }

        return $complaint->load('replies');
    }

    public function getCustomerComplaints($request): LengthAwarePaginator
    {
        return $this->complaintRepository->showCustomerComplaints(
            Auth::id(),
            $request
        );
    }

}
