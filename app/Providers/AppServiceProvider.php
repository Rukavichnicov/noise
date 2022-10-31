<?php

namespace App\Providers;

use App\Contracts\ReportListSourcesForUser;
use App\Services\ReportInWordListSourcesForUser;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReportListSourcesForUser::class, function ($app) {
            return new ReportInWordListSourcesForUser();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
