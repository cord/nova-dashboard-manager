<?php

namespace Marispro\NovaDashboardManager;
use Illuminate\Support\ServiceProvider;

class DashboardManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-dashboard-manager');


    }
}
