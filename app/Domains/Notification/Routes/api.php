<?php

use App\Domains\Complaint\Http\Controllers\ComplaintController;
use App\Domains\Notification\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Notification Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [UserNotificationController::class, 'index']);
    Route::patch('/{notification}/read', [UserNotificationController::class, 'markAsRead']);
});


