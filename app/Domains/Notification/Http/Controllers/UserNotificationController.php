<?php

namespace App\Domains\Notification\Http\Controllers;

use App\Domains\Notification\Http\Requests\LisNotificationRequest;
use App\Domains\Notification\Http\Resources\NotificationCollectionResource;
use App\Domains\Notification\Services\UserNotificationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Notifications\DatabaseNotification;

class UserNotificationController extends Controller
{
    public function __construct(
        protected UserNotificationService $userNotificationService
    ) {}

    public function index(LisNotificationRequest $request): JsonResponse
    {
        $notifications = $this->userNotificationService->listMyNotifications($request);

        return self::Success(new NotificationCollectionResource($notifications));

    }

    public function markAsRead(DatabaseNotification $notification): JsonResponse
    {
        $this->userNotificationService->markMyNotificationAsRead($notification);

        return self::Success(msg: 'Notification marked as read');
    }
}
