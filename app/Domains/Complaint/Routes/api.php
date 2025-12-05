<?php

use App\Domains\Complaint\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Complaint Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/', [ComplaintController::class, 'create'])->middleware(['permission:create-complaint']);
    Route::get('/', [ComplaintController::class, 'index'])->middleware(['permission:show-complaints']);
    Route::get('/my', [ComplaintController::class, 'myComplaints'])->middleware(['permission:show-my-complaints']);
    Route::get('/{complaint}', [ComplaintController::class, 'show'])->middleware(['permission:show-complaint-details']);
    Route::post('/{complaint}/reply', [ComplaintController::class, 'reply'])->middleware(['permission:reply-complaint']);
    Route::post('/{complaint}/change-status', [ComplaintController::class, 'changeStatus'])->middleware(['permission:change-status-complaint']);
    Route::post('/{complaint}/assign', [ComplaintController::class, 'assignToMe']);
    Route::post('/{complaint}/status/optimistic', [ComplaintController::class, 'changeStatusOptimistic']);

});


