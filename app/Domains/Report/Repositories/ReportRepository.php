<?php

namespace App\Domains\Report\Repositories;

use App\Domains\Shared\Tracing\Models\Trace;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ReportRepository implements ReportRepositoryInterface
{
    public function TracingReport($request): LengthAwarePaginator
    {
        $perPage = $request->input('per_page', 20);
        $page    = $request->input('page', 1);

        $cacheKey = 'traces:admin:' . md5(json_encode([
                'page'     => $page,
                'per_page' => $perPage,
                'filter'   => $request->input('filter', []),
                'sort'     => $request->input('sort'),
            ]));

        return Cache::tags(['traces'])
            ->remember($cacheKey, 300, function () use ($request, $perPage) {

                $query = Trace::query()
                    ->with(['user:id,email'])
                    ->latest('occurred_at');

                return QueryBuilder::for($query, $request)
                    ->allowedFilters([
                        AllowedFilter::exact('reference_number'),
                        AllowedFilter::exact('action'),
                        AllowedFilter::exact('status_code'),
                        AllowedFilter::exact('user_id'),
                        AllowedFilter::exact('entity_type'),
                        AllowedFilter::exact('entity_id'),
                        AllowedFilter::callback('from', fn ($q, $v) => $q->where('occurred_at', '>=', $v)),
                        AllowedFilter::callback('to', fn ($q, $v) => $q->where('occurred_at', '<=', $v)),
                    ])
                    ->allowedSorts(['occurred_at', 'status_code', 'action', 'user_id'])
                    ->paginate($perPage)
                    ->appends($request->query());
            });
    }
}
