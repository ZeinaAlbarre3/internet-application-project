<?php

namespace App\Domains\Report\Services;

use App\Domains\Report\Repositories\ReportRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    public function __construct(private ReportRepositoryInterface $repo) {}
    public function getAdminTraceReport($request): LengthAwarePaginator
    {
        return $this->repo->TracingReport($request);
    }
}
