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

        // Load migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        // Config
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('nova-databoards.php'),
        ], 'config');
    }
}
