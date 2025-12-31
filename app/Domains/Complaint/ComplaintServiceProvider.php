<?php

namespace App\Domains\Complaint;

use App\Domains\Complaint\Events\ComplaintAssigned;
use App\Domains\Complaint\Events\ComplaintCreated;
use App\Domains\Complaint\Events\ComplaintReplyCreated;
use App\Domains\Complaint\Events\ComplaintStatusChanged;
use App\Domains\Complaint\Listeners\InvalidateComplaintCacheListener;
use App\Domains\Complaint\Listeners\StoreComplaintHistoryListener;
use App\Domains\Complaint\Repositories\ComplaintRepository;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Domains\Notification\Listeners\SendDatabaseNotificationListener;
use App\Domains\Shared\Tracing\Listeners\StoreTraceListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class ComplaintServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ComplaintRepositoryInterface::class,
            ComplaintRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->registerRoutes();

        $map = [
            ComplaintCreated::class => [
                InvalidateComplaintCacheListener::class,
                StoreComplaintHistoryListener::class,
                StoreTraceListener::class,
                SendDatabaseNotificationListener::class,
            ],

            ComplaintReplyCreated::class => [
                InvalidateComplaintCacheListener::class,
                StoreComplaintHistoryListener::class,
                StoreTraceListener::class,
                SendDatabaseNotificationListener::class,
            ],

            ComplaintStatusChanged::class => [
                InvalidateComplaintCacheListener::class,
                StoreComplaintHistoryListener::class,
                StoreTraceListener::class,
                SendDatabaseNotificationListener::class,
            ],

            ComplaintAssigned::class => [
                InvalidateComplaintCacheListener::class,
                StoreComplaintHistoryListener::class,
            ],
        ];

        foreach ($map as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Register the routes for the domain.
     */
    protected function registerRoutes(): void
    {
        Route::prefix('api/v1/complaints')
             ->middleware('api')
             ->group(__DIR__.'/Routes/api.php');
    }
}
