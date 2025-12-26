<?php

use App\Domains\Report\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/trace', [ReportController::class, 'traceReport']);//->middleware(['permission:trace-report']);
});
