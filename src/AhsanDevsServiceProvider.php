<?php

namespace AhsanDevs;

use Illuminate\Support\ServiceProvider;

class AhsanDevsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            Console\InstallCommand::class,
        ]);
    }
}
