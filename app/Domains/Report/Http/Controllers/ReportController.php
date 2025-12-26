<?php

namespace App\Domains\Report\Http\Controllers;

use App\Domains\Report\Http\Requests\LisReportTraceRequest;
use App\Domains\Report\Http\Resources\TraceResourceListCollection;
use App\Domains\Report\Services\ReportService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    use ResponseTrait;
    public function __construct(private ReportService $service) {}

    public function traceReport(LisReportTraceRequest $request): JsonResponse
    {
        $traces = $this->service->getAdminTraceReport($request);

        return self::Success(data: new TraceResourceListCollection($traces));

    }
}
