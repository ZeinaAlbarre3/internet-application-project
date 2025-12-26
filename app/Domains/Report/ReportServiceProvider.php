<?php

namespace App\Domains\Report;

use App\Domains\Complaint\Events\ComplaintCreated;
use App\Domains\Complaint\Events\ComplaintReplyCreated;
use App\Domains\Complaint\Events\ComplaintStatusChanged;
use App\Domains\Complaint\Listeners\InvalidateComplaintCacheListener;
use App\Domains\Complaint\Repositories\ComplaintRepository;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
use App\Domains\Report\Repositories\ReportRepository;
use App\Domains\Report\Repositories\ReportRepositoryInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;


class ReportServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ReportRepositoryInterface::class,
            ReportRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

        $this->registerRoutes();
    }

    /**
     * Register the routes for the domain.
     */
    protected function registerRoutes(): void
    {
        Route::prefix('api/v1/reports')
             ->middleware('api')
             ->group(__DIR__.'/Routes/api.php');
    }
}
