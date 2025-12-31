<?php

namespace App\Domains\Notification\Services;

use App\Domains\Auth\Models\User;
use App\Domains\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class UserNotificationService
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepository
    ) {}

    public function listMyNotifications($request): LengthAwarePaginator
    {
        return $this->notificationRepository->paginateForUser(Auth::user(), $request);
    }

    public function markMyNotificationAsRead(DatabaseNotification $notification): void
    {
        $this->notificationRepository->markAsRead($notification);
    }
}
