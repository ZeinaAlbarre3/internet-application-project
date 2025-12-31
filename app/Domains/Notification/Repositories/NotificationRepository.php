<?php

namespace App\Domains\Notification\Repositories;


use App\Domains\Auth\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function paginateForUser(User $user, $request): LengthAwarePaginator
    {
        $perPage = $request['per_page'] ?? 20;

        $q = $user->notifications()->latest();

        return $q->paginate($perPage);
    }

    public function markAsRead(DatabaseNotification $notification): void
    {
        $notification->markAsRead();
    }
}
