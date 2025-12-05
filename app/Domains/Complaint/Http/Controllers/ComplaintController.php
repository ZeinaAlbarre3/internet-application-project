<?php

namespace App\Domains\Complaint\Http\Controllers;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Data\CreateComplaintData;
use App\Domains\Complaint\Data\ReplyComplaintData;
use App\Domains\Complaint\Http\Requests\ChangeComplaintStatusRequest;
use App\Domains\Complaint\Http\Requests\CreateComplaintRequest;
use App\Domains\Complaint\Http\Requests\ListComplaintsRequest;
use App\Domains\Complaint\Http\Requests\ReplyComplaintRequest;
use App\Domains\Complaint\Http\Resources\ComplaintListResourceCollection;
use App\Domains\Complaint\Http\Resources\ComplaintResource;
use App\Domains\Complaint\Models\Complaint;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Domains\Complaint\Services\ComplaintService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ComplaintController extends Controller
{
    use ResponseTrait;
    public function __construct(
        protected ComplaintRepositoryInterface $complaintRepository,
        protected ComplaintService $complaintService
    )
    {
    }

    public function create(CreateComplaintRequest $request): JsonResponse
    {
        $complaintData = CreateComplaintData::from($request->validated());

        $complaint = $this->complaintService->createComplaint($complaintData);

        return self::Success(new ComplaintResource($complaint),msg:'Complaint has been created successfully');
    }

    public function index(ListComplaintsRequest $request): JsonResponse
    {
        $complaints = $this->complaintRepository->showAllComplaints($request);

        return self::Success(data: new ComplaintListResourceCollection($complaints));
    }

    public function show(Complaint $complaint): JsonResponse
    {
        $complaint = $this->complaintService->showComplaint($complaint);

        return self::Success(data: new ComplaintResource($complaint));
    }

    public function myComplaints(ListComplaintsRequest $request): JsonResponse
    {
        $complaints = $this->complaintService->getCustomerComplaints($request);

        return self::Success(data: new ComplaintListResourceCollection($complaints));
    }

    public function reply(ReplyComplaintRequest $request, Complaint $complaint): JsonResponse
    {
        $replyData = ReplyComplaintData::from($request->validated());

        $complaint = $this->complaintService->replyToComplaint($complaint, $replyData);

        return self::Success(new ComplaintResource($complaint),msg: 'Complaint has been replied successfully');
    }

    public function changeStatus(ChangeComplaintStatusRequest $request,Complaint $complaint): JsonResponse
    {
        $statusData = ChangeStatusData::from($request->validated());

        $complaint = $this->complaintService->changeStatus($complaint, $statusData);

        return self::Success(new ComplaintResource($complaint),msg: 'Complaint status has been changed successfully');

    }

    public function assignToMe(Complaint $complaint): JsonResponse
    {
        try {
            $complaint = $this->complaintService->assignToMe($complaint);

            return self::Success(
                new ComplaintResource($complaint),
                msg: 'Complaint assigned to you successfully'
            );

        } catch (\App\Exceptions\Types\CustomException $e) {

            return self::Error(
                data: null,
                msg: $e->getMessage(),
                code: $e->getCode() ?: 400
            );
        }
    }

    public function changeStatusOptimistic(ChangeComplaintStatusRequest $request, Complaint $complaint): JsonResponse
    {
        $data = ChangeStatusData::from($request->validated());
        try {
            $complaint = $this->complaintService->changeStatusOptimistic($complaint, $data);
            return self::Success(new ComplaintResource($complaint), msg: 'Complaint status changed (optimistic)');
        } catch (\App\Exceptions\Types\CustomException $e) {
            return self::Error(null, $e->getMessage(), $e->getCode() ?: 409);
        }
    }



}
