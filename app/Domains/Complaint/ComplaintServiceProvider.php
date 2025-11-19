<?php

namespace App\Domains\Complaint;

use App\Domains\Complaint\Repositories\ComplaintRepository;
use App\Domains\Complaint\Repositories\ComplaintRepositoryInterface;
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
