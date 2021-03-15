<?php

namespace Eduka\Analytics;

use Illuminate\Support\ServiceProvider;

class EdukaAnalyticsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->importMigrations();
        $this->publishResources();
    }

    public function register()
    {
        //
    }

    protected function importMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function publishResources()
    {
        $this->publishes([
            __DIR__.'/../resources/overrides/' => base_path('/'),
        ]);
    }
}
