<?php

namespace App\Domains\Complaint\Repositories;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Enum\ComplaintStatusEnum;
use App\Domains\Complaint\Models\Complaint;
use App\Exceptions\Types\CustomException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComplaintRepository implements ComplaintRepositoryInterface
{
    public function create(array $attributes)
    {
        return Complaint::create($attributes);
    }

    public function showAllComplaints($request): LengthAwarePaginator
    {
        $complaints = Complaint::query()->latest();

        return QueryBuilder::for($complaints , $request)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('is_read')
            ])
            ->allowedSorts(['expected_cost', 'created_at', 'id'])

            ->paginate($request['per_page'] ?? 16);
    }

    public function showCustomerComplaints(int $userId, $request): LengthAwarePaginator
    {
        return Complaint::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($request['per_page'] ?? 16);
    }

    public function createReply(Complaint $complaint, array $attributes)
    {
        return $complaint->replies()->create($attributes);
    }

    public function updateStatus(Complaint $complaint, ChangeStatusData $data): Complaint
    {
        $complaint->update([
            'status' => $data->status->value,
        ]);

        return $complaint;
    }

    public function assignToStaffAtomic(Complaint $complaint, int $staffId): bool
    {
        $updated = Complaint::where('id', $complaint->id)
            ->whereNull('assigned_to')
            ->update([
                'assigned_to' => $staffId,
                'status' => ComplaintStatusEnum::IN_PROGRESS->value,
            ]);

        return $updated > 0;
    }

    public function assignToStaffWithLock(Complaint $complaint, int $staffId, int $seconds = 10): bool
    {
        $lockKey = "complaint-assign-{$complaint->id}";

        $lock = Cache::lock($lockKey, $seconds);

        if (!$lock->get()) {
            return false;
        }

        try {
            $fresh = Complaint::find($complaint->id);
            if ($fresh->assigned_to !== null) {
                return false;
            }

            $fresh->assigned_to = $staffId;
            $fresh->status = ComplaintStatusEnum::IN_PROGRESS->value;
            $fresh->save();

            return true;
        } finally {
            $lock->release();
        }
    }

    public function updateStatusOptimistic(Complaint $complaint, ChangeStatusData $data): Complaint
    {
        $oldVersion = $data->version ?? ($complaint->version ?? 1);

        $updated = Complaint::where('id', $complaint->id)
            ->where('version', $oldVersion)
            ->update([
                'status' => $data->status->value,
                'version' => $oldVersion + 1,
            ]);

        if ($updated === 0) {
            throw new CustomException('Conflict: complaint was updated by someone else', 409);
        }

        return Complaint::find($complaint->id);
    }

}
