<?php

namespace App\Domains\Complaint\Repositories;

use App\Domains\Complaint\Data\ChangeStatusData;
use App\Domains\Complaint\Models\Complaint;
use Illuminate\Pagination\LengthAwarePaginator;
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
}
