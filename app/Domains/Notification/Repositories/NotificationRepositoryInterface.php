<?php

namespace App\Domains\Notification\Repositories;

use App\Domains\Auth\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Notifications\DatabaseNotification;

interface NotificationRepositoryInterface
{
    public function paginateForUser(User $user, $request): LengthAwarePaginator;

    public function markAsRead(DatabaseNotification $notification): void;
}
