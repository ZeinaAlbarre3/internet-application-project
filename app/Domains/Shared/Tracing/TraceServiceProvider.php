<?php

namespace App\Domains\Shared\Tracing;

use App\Domains\Auth\Events\LoginFailed;
use App\Domains\Auth\Events\LoginSucceeded;
use App\Domains\Auth\Events\PasswordResetCompleted;
use App\Domains\Auth\Events\RegisterOtpVerified;
use App\Domains\Complaint\Events\ComplaintCreated;
use App\Domains\Complaint\Events\ComplaintReplyCreated;
use App\Domains\Complaint\Events\ComplaintStatusChanged;
use App\Domains\Complaint\Repositories\ComplaintRepository;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Domains\Shared\Tracing\Listeners\StoreTraceListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class TraceServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $events = [
            // Auth
            LoginSucceeded::class,
            LoginFailed::class,
            RegisterOtpVerified::class,
            PasswordResetCompleted::class,

            // Complaints
            ComplaintCreated::class,
            ComplaintReplyCreated::class,
            ComplaintStatusChanged::class,
        ];

        foreach ($events as $event) {
            Event::listen($event, StoreTraceListener::class);
        }
    }
}
