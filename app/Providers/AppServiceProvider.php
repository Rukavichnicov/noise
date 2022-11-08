<?php

namespace App\Providers;

use App\Contracts\ArchiveFileSourcesForUser;
use App\Contracts\ReportListSourcesForUser;
use App\Services\ReportInWordListSourcesForUser;
use App\Services\ZipArchiveFileSourcesForUser;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
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
        $this->app->bind(ArchiveFileSourcesForUser::class, function ($app) {
            return new ZipArchiveFileSourcesForUser();
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
