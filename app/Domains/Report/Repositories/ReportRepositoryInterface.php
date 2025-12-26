<?php

namespace App\Domains\Report\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface
{
    public function TracingReport($request): LengthAwarePaginator;
}
